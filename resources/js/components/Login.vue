<template>
    <div class="login-container">
        <div class="row justify-content-center">
            <div class="col-md-8 rounded-0">
                <div class="card card-default rounded-0 border-0">
                    <div class="card-header">LOGIN</div>

                    <div class="card-body rounded-0 p-0">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-3 p-0 login-img d-flex align-items-end">
                                    <h5><span class="orange">fused</span>ship</h5>
                                </div>
                                <div class="col-lg-2 login-col-two p-0">
                                    <button type="button" class="btn btn-success rounded-0 p-0">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <p>Login</p>
                                    </button>
                                    <button type="button" class="btn btn-light rounded-0 p-0">
                                        <i class="fad fa-luchador"></i>
                                        <p>Forgot Password</p>
                                    </button>
                                </div>
                                <div class="col-lg-7 rounded-0 bg-white">
                                    <form method="POST" action="/login">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input id="email" type="email" class="form-control rounded-0" placeholder="Email" v-model="email" required autofocus>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input id="password" type="password" class="form-control rounded-0" placeholder="Password" v-model="password" required>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success rounded-0" @click="handleSubmit">
                                                    Login
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <v-snackbar
            v-model="isShow"
            color="#d55"
            :multi-line="true"
            :center="true"
            :timeout="3000"
            :top="true">
            {{ message }}
            <v-btn dark text @click="isShow = false">
                Close
            </v-btn>
        </v-snackbar>
    </div>
</template>
<script>

    export default {
        data(){
            return {
                email : "",
                password : "",
                isShow: false,
                message: ""
            }
        },
        methods : {
            handleSubmit(e){
                e.preventDefault()

                if (this.password.length > 0) {
                    axios.post('api/auth/token', {
                        withCredentials: true,
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        }
                        },{
                        auth: {
                            username: this.email,
                            password: this.password
                        }
                    }).then((response) => {
                        localStorage.setItem('jwt', response.data.data.attributes.token)
                        if (localStorage.getItem('jwt') != null){
                            this.$router.go('/')
                        }
                    }).catch(err => {
                        console.error(err.response);
                        this.isShow = true
                        if (err.response.status == 401) {
                            this.message = "Incorrect login credentials, Please try again."
                        } else {
                            this.message = this.getErrors(err)
                        }
                    });

                }
            }
        },
        beforeRouteEnter (to, from, next) { 
            if (localStorage.getItem('jwt')) {
                return next('dashboard');
            }

            next();
        }
    }
</script>