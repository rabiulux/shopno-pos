<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>EMAIL ADDRESS</h4>
                    <br/>
                    <label>Your email address</label>
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <button onclick="sendOTP()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function sendOTP() {
        const email = document.getElementById('email').value;

        if (email.length === 0) {
            errorToast('Please enter your email');
        } else {
            try {
                showLoader();
                let response = await axios.post('/send-otp', { email: email });
                hideLoader();

                if (response.status === 200 && response.data.status === 'success') {
                    successToast('OTP sent successfully');
                    sessionStorage.setItem('email', email);
                    setTimeout(() => {
                        window.location.href = '/verify-otp';
                    }, 1000);
                } else {
                    errorToast(response.data.message || 'Failed to send OTP');
                }
            } catch (error) {
                hideLoader();
                errorToast(error.response?.data?.message || 'An unexpected error occurred');
            }
        }
    }
</script>
