<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 mt-5 mx-auto">
        <form v-on:submit.prevent="register">
          <h1 class="h3 mb-3 font-weight-normal">Register</h1>

					<div class="alert alert-danger" role="alert" v-if="this.errors!=''">
						<ul>
							<li v-for='error in this.errors' :key="error.id">
								{{ error }}
							</li>
						</ul>
					</div>

          <div class="form-group">
            <label for="last_name"> User Name</label>
            <input type="text" v-model="user_name" class="form-control" name="user_name" placeholder="User name">
          </div>

          <div class="form-group">
            <label for="email"> Email Address</label>
            <input type="email" v-model="email" class="form-control" name="email" placeholder="Email Address">
          </div>

          <div class="form-group">
            <label for="last_name"> Phone Number</label>
            <input type="text" v-model="phone_number" class="form-control" name="phone_number" placeholder="Phone Number">
          </div>

          <div class="form-group">
            <label for="password"> Password</label>
            <input type="password" v-model="password" class="form-control" name="password" placeholder="Password">
          </div>

          <div class="form-group">
            <label for="password"> Confirm Password</label>
            <input type="password" v-model="password_confirmation" class="form-control" name="password_confirmation" placeholder="Password">
          </div>

          <button class="btn btn-lg btn-primary btn-block">Register</button>
        </form>
      </div>
    </div>
  </div>

</template>


<script>
    import axios from 'axios'
    import router from '../router'

    export default{
        data(){
            return {
                user_name: '',
                email: '',
                phone_number: '',
                password: '',
                password_confirmation: '',
								errors: []
            }
        },

        methods:{
            register() {
                axios.post('http://127.0.0.1:8000/api/users/register',
                    {
                        name:this.user_name,
                        email:this.email,
                        phone_number:this.phone_number,
                        password:this.password,
                        password_confirmation:this.password_confirmation,
                    })
                    .then((res) => {
                        console.log(res)
                        router.push({name: 'Login', query: { msg: 'registerSuccess' }})
                    })
                    .catch((err) => {
											let errors = err.response.data.message
												// console.log(errors);
												// errors.forEach(error => {
												// 	console.log(error);
												// });
												this.errors = []
												for (const [key, value] of Object.entries(errors)) {
													// console.log(`${key}: ${value}`);
													this.errors.push(value[0]);
												}
                        // console.log(this.errors)
                    })
            },
        }
    }
</script>