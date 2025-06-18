<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90 p-4">
                <div class="card-body">
                    <h4>SET NEW PASSWORD</h4>
                    <br/>
                    <label>New Password</label>
                    <input id="password" placeholder="New Password" class="form-control" type="password"/>
                    <br/>
                    <label>Confirm Password</label>
                    <input id="cpassword" placeholder="Confirm Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="ResetPass()" class="btn w-100 bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function ResetPass() {
        const password = document.getElementById('password').value;
        const cpassword = document.getElementById('cpassword').value;

        if (password.length === 0 || cpassword.length === 0) {
            errorToast('Please fill in all fields');
        }else if (password.length < 8) {
            errorToast('Password must be at least 8 characters long');
        } else if (password !== cpassword) {
            errorToast('Passwords do not match');
        } else {
            try {
                showLoader();
                let response = await axios.post('/reset-password', { password: password });
                hideLoader();

                if (response.status === 200 && response.data.status === 'success') {
                    successToast('Password reset successfully');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1000);
                } else {
                    errorToast(response.data.message || 'Password reset failed');
                }
            } catch (error) {
                hideLoader();
                errorToast(error.response?.data?.message || 'An unexpected error occurred');
            }
        }
    }
</script>
