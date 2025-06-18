<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Sign Up</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">

                            <div class="col-md-4 p-2">
                                <label>Name</label>
                                <input id="name" placeholder="Full Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email"/>
                            </div>

                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Confirm Password</label>
                                <input id="password_confirmation" placeholder="Confirm Password" class="form-control" type="password"/>
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onRegistration()" class="btn mt-3 w-100  bg-gradient-primary">Complete</button>
                            </div>
                            <div class="float-end mt-3">
                                <span>

                                    <span class="ms-1">Already have an account?</span>
                                    <a class="text-center ms-3 h6" href="{{url('/login')}}">Login</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function onRegistration() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const mobile = document.getElementById('mobile').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;

        if (name.length === 0) {
            errorToast('Please enter your name');
        } else if (email.length === 0) {
            errorToast('Please enter your email');
        } else if (mobile.length === 0) {
            errorToast('Please enter your mobile number');
        } else if (password.length === 0) {
            errorToast('Please enter your password');
        } else if (password_confirmation.length === 0) {
            errorToast('Please confirm your password');
        } else if (password !== password_confirmation) {
            errorToast('Passwords do not match');
        } else {
            try {
                showLoader();
                let response = await axios.post('/registration', {
                    name: name,
                    email: email,
                    mobile: mobile,
                    password: password,
                    password_confirmation: password_confirmation
                });
                hideLoader();

                if (response.status === 200 && response.data.status === 'success') {
                    successToast(response.data.message || 'Registration successful');
                    setTimeout(function(){
                        window.location.href = '/login';
                    }, 1500);

                } else {
                    errorToast(response.data.message || 'Registration failed');
                }
            } catch (error) {
                hideLoader();
                errorToast(error.response?.data?.message || 'An unexpected error occurred');
            }
        }
    }



</script>
