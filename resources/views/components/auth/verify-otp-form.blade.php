<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>ENTER OTP CODE</h4>
                    <br/>
                    <label>6 Digits Code Here</label>
                    <input id="otp" placeholder="Code" class="form-control" type="text"/>
                    <br/>
                    <button onclick="VerifyOtp()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function VerifyOtp() {
        const otp = document.getElementById('otp').value;
        const email = sessionStorage.getItem('email');

        if(otp.length === 0) {
            errorToast('Please enter the OTP');
        }else if (otp.length !== 6) {
            errorToast('Invalid OTP');
        } else {
            try {
                showLoader();
                let response = await axios.post('/verify-otp', { email: email, otp: otp });
                hideLoader();

                if (response.status === 200 && response.data.status === 'success') {
                    successToast('OTP verified successfully');
                    sessionStorage.removeItem('email'); // Clear the email from session storage
                    setTimeout(() => {
                        window.location.href = '/reset-password';
                    }, 1000);
                } else {
                    errorToast(response.data.message || 'OTP verification failed');
                }
            } catch (error) {
                hideLoader();
                errorToast(error.response?.data?.message || 'An unexpected error occurred');
            }
        }
    }

</script>
