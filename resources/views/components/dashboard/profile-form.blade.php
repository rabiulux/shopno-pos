<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>User Profile</h4>
                    <hr />
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input readonly id="email" placeholder="User Email" class="form-control"
                                    type="email" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Name</label>
                                <input id="name" placeholder="Name" class="form-control" type="text" />
                            </div>

                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="Password" class="form-control" type="password" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Confirm Password</label>
                                <input id="password_confirmation" placeholder="Confirm Password" class="form-control"
                                    type="password" />
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onUpdate()" class="btn mt-3 w-100  bg-gradient-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    getProfile();

    async function getProfile() {
        try {
            showLoader();
            let response = await axios.get('/profile-data');
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const user = response.data.user;
                document.getElementById('email').value = user.email || '';
                document.getElementById('name').value = user.name || '';
                document.getElementById('mobile').value = user.mobile || '';
                document.getElementById('password').value = user.password || '';
                document.getElementById('password_confirmation').value = user.password || '';

            } else {
                errorToast(response.data.message || 'Failed to load profile');
            }
        } catch (error) {
            hideLoader();
            errorToast(error.response?.data?.message || 'An unexpected error occurred');
        }
    }


    async function onUpdate() {
        const name = document.getElementById('name').value;
        const mobile = document.getElementById('mobile').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;

        if (password.length > 0) {
            if (password_confirmation.length === 0) {
                errorToast('Please confirm your password');
            } else if (password !== password_confirmation) {
                errorToast('Passwords do not match');
            }
        }

        if (name.length === 0) {
            errorToast('Please enter your name');
        } else if (mobile.length === 0) {
            errorToast('Please enter your mobile number');
        } else {
            try {
                showLoader();
                let response = await axios.post('/profile-update', {
                    name: name,
                    mobile: mobile,
                    password: password,
                    password_confirmation: password_confirmation
                });
                hideLoader();

                if (response.status === 200 && response.data.status === 'success') {
                    successToast(response.data.message || 'Registration successful');
                    await getProfile();

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
