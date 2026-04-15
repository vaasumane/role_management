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
let page = 1;
let loading = false;
let lastPage = false;
let search = "";

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
function renderVoters(data, append = false) {
    let html = "";

    data.forEach((voter) => {
        html += `
        <div class="col-md-4 mb-3 ">
            <div class="card shadow-sm p-3 card-grid">
                <h5>${voter.applicant_full_name}</h5>
                <p><strong>EPIC:</strong> ${voter.epic_number}</p>
                <p><strong>Age:</strong> ${voter.age} | ${voter.gender}</p>
                <p><strong>Part:</strong> ${voter.booth_address}</p>
                <p><strong>Assembly:</strong> ${voter.assem_name}</p>
            </div>
        </div>`;
    });

    if (append) {
        $("#voterGrid").append(html);
    } else {
        $("#voterGrid").html(html);
    }
}
$(document).ready(function () {
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

    $(window).scroll(function () {
        if (
            $(window).scrollTop() + $(window).height() >=
            $(document).height() - 100
        ) {
            loadVoters();
        }
    });
    $("#voterGrid").scroll(function () {
        if (
            $(this).scrollTop() + $(this).innerHeight() >=
            this.scrollHeight - 50
        ) {
            loadVoters();
        }
    });
    $("#searchBox").keyup(function () {
        search = $(this).val();

        page = 1;
        lastPage = false;

        $("#voterGrid").html("");

        loadVoters();
    });
    if (loading || lastPage) return;
});
