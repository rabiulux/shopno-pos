<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 animated fadeIn col-lg-6 center-screen">
            <div class="card w-90  p-4">
                <div class="card-body">
                    <h4>SIGN IN</h4>
                    <br/>
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <input id="password" placeholder="User Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="SubmitLogin()" class="btn w-100 bg-gradient-primary">Next</button>
                    <hr/>
                    <div class="float-end mt-3">
                        <span>
                            <a class="text-center ms-3 h6" href="{{url('/user-registration')}}">Sign Up </a>
                            <span class="ms-1">|</span>
                            <a class="text-center ms-3 h6" href="{{url('/send-otp')}}">Forget Password</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function SubmitLogin(){
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if(email.length === 0){
        errorToast('Please enter your email');
    }
    else if(password.length === 0){
        errorToast('Please enter your password');
    }
    else{
        try {
            showLoader();
            let response = await axios.post('/user-login', {
                email: email,
                password: password
            });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                window.location.href = '/dashboard';
            } else {
                errorToast(response.data.message || 'Login failed');
            }
        } catch (error) {
            hideLoader();
            errorToast(error.response?.data?.message || 'An unexpected error occurred');
        }
    }
}

</script>
