document.addEventListener("DOMContentLoaded", function () {
    let page = localStorage.getItem("page") || "mobile";

    let mobileForm = document.getElementById("MobileEnterForm");
    let loginForm = document.getElementById("LoginForm");
    let otpForm = document.getElementById("VerifyOtpForm");

    // Hide all
    if (mobileForm) mobileForm.style.display = "none";
    if (loginForm) loginForm.style.display = "none";
    if (otpForm) otpForm.style.display = "none";

    // Show based on page
    if (page === "mobileverify") {
        if (otpForm) otpForm.style.display = "block";
    } else if (page === "login") {
        if (loginForm) loginForm.style.display = "block";
    } else {
        if (mobileForm) mobileForm.style.display = "block";
    }
});
function backfunction() {
    localStorage.setItem("page", "");
    localStorage.setItem("mobile", "");
    location.reload();
}
function clearFilter() {
    page = 1;
    loading = false;
    lastPage = false;
    search = "";
    $("#voterGrid").html("");
    $("#partSelect").val("");
    $("#assemblySelect").val("");
}
function loadVoters() {
    if (loading || lastPage) return;

    loading = true;
    $("#loader").show();
    part_id = $("#partSelect option:selected").val();
    ac_id = $("#assemblySelect option:selected").val();
    $.ajax({
        url: base_url + "voters",
        type: "GET",
        data: {
            page: page,
            search: search,
            part_id: part_id,
            ac_id: ac_id,
            _token: $("input[name=_token]").val(),
        },
        success: function (res) {
            renderVoters(res.data, true); // append mode

            if (page >= res.last_page) {
                lastPage = true;
            } else {
                page++;
            }

            loading = false;
            $("#loader").hide();
        },
        error: function () {
            loading = false;
            $("#loader").hide();
        },
    });
}
$(document).ready(function () {
    let table = $("#voterTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "voters",
            type: "GET",
            data: function (d) {
                d.part_id = $("#partSelect").val();
                d.ac_id = $("#assemblySelect").val();
                d.search_value = $("#searchBox").val();
            },
        },
        columns: [
            { data: "id" },
            { data: "applicant_full_name" },
            { data: "age" },
            { data: "gender" },
            { data: "epic_number" },
            { data: "booth_address" },
            { data: "assem_name" },
            {
                data: null,
                render: function (data) {
                    return `
                <button class="btn btn-sm btn-primary editBtn"
                    data-id="${data.id}">
                    Edit
                </button>
            `;
                },
            },
        ],
        pageLength: 10,
    });
    // Reload on filter change
    $("#applyfilter,#clearFilter").click(function () {
        table.ajax.reload();
    });

    $("#MobileEnterForm").submit(function (e) {
        e.preventDefault();

        let mobile = $("#mobile_no").val();

        if (mobile.length != 10) {
            alert("Enter valid 10-digit mobile number");
            return;
        }
        $("#MobileVerfication").attr("disabled", true).html("Loading...");

        $.ajax({
            url: base_url + "check-user", // Laravel route
            type: "POST",
            data: {
                mobile: mobile,
                _token: $("input[name=_token]").val(),
            },
            success: function (response) {
                $("#MobileVerfication")
                    .attr("disabled", false)
                    .html("Continue");

                if (response.status === "verified") {
                    // User exists → go to login
                    localStorage.setItem("page", "login");
                } else if (response.status === "not_verified") {
                    // Send OTP
                    localStorage.setItem("page", "mobileverify");

                    // Optional: store mobile
                    localStorage.setItem("mobile", mobile);
                }

                location.reload();
            },
            error: function () {
                $("#MobileVerfication")
                    .attr("disabled", false)
                    .html("Continue");

                alert("Something went wrong");
            },
        });
    });
    $("#VerifyOtpForm").submit(function (e) {
        e.preventDefault();

        let mobile = localStorage.getItem("mobile") ?? $("#mobile_no").val();
        let otp = $("#otp_code").val();

        if (otp.length != 4) {
            alert("Enter valid OTP");
            return;
        }
        $("#OtpMobileVerfication").attr("disabled", true).html("Loading...");

        $.ajax({
            url: base_url + "verify-otp",
            type: "POST",
            data: {
                mobile_number: mobile,
                otp_code: otp,
                _token: $("input[name=_token]").val(),
            },
            success: function (response) {
                $("#OtpMobileVerfication")
                    .attr("disabled", false)
                    .html("Verify");

                if (response.status === "success") {
                    localStorage.setItem("page", "login");
                    location.reload();
                } else {
                    alert("Something went wrong");
                }
            },
            error: function () {
                $("#OtpMobileVerfication")
                    .attr("disabled", false)
                    .html("Verify");

                alert("Something went wrong");
            },
        });
    });
    $("#LoginForm").submit(function (e) {
        e.preventDefault();

        let mobile = $("#mob_no").val();
        let password = $("#password").val();

        if (!mobile || password === "") {
            alert("Enter mobile and password");
            return;
        }

        $("#LoginBtn").attr("disabled", true).html("Loading...");

        $.ajax({
            url: base_url + "verify-password",
            type: "POST",
            data: {
                mobile_number: mobile,
                password: password,
                _token: $("input[name=_token]").val(),
            },
            success: function (response) {
                $("#LoginBtn").attr("disabled", false).html("Login");

                if (response.status === "success") {
                    alert(response.message);

                    // clear localStorage
                    localStorage.removeItem("page");

                    // redirect to dashboard
                    window.location.href = base_url + "voter-list";
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                $("#LoginBtn").attr("disabled", false).html("Login");
                alert("Something went wrong");
            },
        });
    });
    $("#partSelect").change(function () {
        let partId = $(this).val();
        let assemblies = partAssemblyData[partId];

        let options = '<option value="">Select Assembly</option>';

        if (assemblies) {
            assemblies.forEach(function (item) {
                options += `<option value="${item.assembly_id}">
                ${item.assembly_id} - ${item.assembly_name}
            </option>`;
            });
        }

        $("#assemblySelect").html(options);
    });
    $("#part_id").change(function () {
        let partId = $(this).val();
        let assemblies = partAssemblyData[partId];

        let options = '<option value="">Select Assembly</option>';

        if (assemblies) {
            assemblies.forEach(function (item) {
                options += `<option value="${item.assembly_id}">
                ${item.assembly_id} - ${item.assembly_name}
            </option>`;
            });
        }

        $("#ac_id").html(options);
    });
    $(document).on("click", ".editBtn", function () {
        console.log($(this).data("id"));

        let voter_id = $(this).data("id");

        $.ajax({
            url: base_url + "get-voter", // Laravel route
            type: "get",
            data: {
                voter_id: voter_id,
                _token: $("input[name=_token]").val(),
            },
            success: function (response) {
                if (response.status) {
                    AssemOp =
                        "<option selected value='" +
                        response.data.acid +
                        "'>" +
                        response.data.assem_name +
                        "</option>";
                    $("#edit_id").val(response.data.id);
                    $("#applicant_full_name").val(
                        response.data.applicant_full_name || "",
                    );
                    $("#applicant_first_name").val(
                        response.data.applicant_first_name || "",
                    );
                    $("#applicant_last_name").val(
                        response.data.applicant_last_name || "",
                    );
                    $("#age").val(response.data.age || "");
                    $("#gender").val(response.data.gender || "");
                    $("#relation").val(response.data.relation || "");
                    $("#part_id").val(response.data.part_id || "");
                    $("#ac_id").html(AssemOp);
                    $("#realtion_full_name").val(
                        response.data.realtion_full_name || "",
                    );
                    $("#realtion_last_name").val(
                        response.data.realtion_last_name || "",
                    );
                    $("#epic_number").val(response.data.epic_number || "");
                    $("#booth_address").val(response.data.booth_address || "");
                    $("#mobile").val(response.data.mobile || "");
                    $("#status").val(response.data.status || "");
                    $("#color").val(response.data.color || "");
                    $("#religions").val(response.data.religions || "");
                    $("#occupations").val(response.data.occupations || "");
                    $("#castes").val(response.data.castes || "");
                    $("#educations").val(response.data.educations || "");
                    $("#languages").val(response.data.languages || "");
                    $(
                        'input[name="visited_status"][value="' +
                            response.data.visited_status +
                            '"]',
                    ).prop("checked", true);
                    $(
                        'input[name="status1"][value="' +
                            response.data.status1 +
                            '"]',
                    ).prop("checked", true);
                    $(
                        'input[name="status2"][value="' +
                            response.data.status2 +
                            '"]',
                    ).prop("checked", true);
                    $(
                        'input[name="status3"][value="' +
                            response.data.status3 +
                            '"]',
                    ).prop("checked", true);
                    $(
                        'input[name="status4"][value="' +
                            response.data.status4 +
                            '"]',
                    ).prop("checked", true);
                    $("#editModal").modal("show");
                }
            },
            error: function () {
                $("#MobileVerfication")
                    .attr("disabled", false)
                    .html("Continue");

                alert("Something went wrong");
            },
        });
    });
    $("#mobile_no").on("input", function () {
        this.value = this.value.replace(/[^0-9]/g, "");
    });

    $("#UpdateVoter").submit(function (e) {
        e.preventDefault();

        let formdata = new FormData(this); // ✅ correct

        $("#updateBtn").attr("disabled", true).html("Loading...");

        $.ajax({
            url: base_url + "update-voter", // ✅ correct route
            type: "POST",
            data: formdata,
            processData: false, // ✅ important
            contentType: false, // ✅ important

            success: function (response) {
                if (response.status) {
                    alert("Updated successfully");
                    $("#editModal").modal("hide");
                    $("#voterTable").DataTable().ajax.reload(null, false);
                } else {
                    alert("Update failed");
                }

                $("#updateBtn").attr("disabled", false).html("Update");
            },

            error: function (xhr) {
                // Clear old errors
                $(".error-text").text("");
                $(".form-control").removeClass("is-invalid");

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        $("#" + key).addClass("is-invalid");
                        $("#" + key + "_error").text(value[0]);
                    });
                } else {
                    alert("Something went wrong");
                }

                $("#updateBtn").attr("disabled", false).html("Update");
            },
        });
    });
});
