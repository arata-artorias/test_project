<template>
  <div class="container">
    <div class="row">
        <div class="col-md-6 mt-5 mx-auto">
            <form v-on:submit.prevent="login">
              <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
              <div class="alert alert-success" role="alert" v-if="this.msg=='registerSuccess'">
                Success Register
              </div>
              <div class="alert alert-danger" role="alert" v-if="this.errors!=''">
                <ul>
                  <li v-for='error in this.errors' :key="error.id">
                    {{ error }}
                  </li>
                </ul>
              </div>

              <div class="form-group">
                <label for="email"> Email Address</label>
                <input type="email" v-model="email" class="form-control" name="email" placeholder="Email Address">
              </div>

              <div class="form-group">
                <label for="password"> Password</label>
                <input type="password" v-model="password" class="form-control" name="password" placeholder="Password">
              </div>

              <button class="btn btn-lg btn-primary btn-block">Sign in</button>
            </form>
      </div>
    </div>
  </div>

</template>


<script>
import axios from 'axios'
import router from '../router'
import EventBus from '../components/EventBus'

export default{
  data(){
    return {
      email: '',
      password: '',
      msg: this.$route.query.msg,
      errors: []
    }
  },

    methods:{
      login() {
        axios.post('http://127.0.0.1:8000/api/users/login',
            {
              email:this.email,
              password:this.password,
            })
            .then((res) => {
              console.log(res);
              localStorage.setItem('usertoken', res.data.token)
                this.email = ''
                this.password = ''
                router.push({name: 'Search'})
            })
            .catch((err) => {
                let errors = err.response.data.message
                this.errors = []
                if (typeof errors == "object") {
                  for (const [key, value] of Object.entries(errors)) {
									  this.errors.push(value[0]);
								  }
                } else {
                  this.errors.push(errors);
                }
            })

          this.emitMethod()
      },

      emitMethod() {
        EventBus.$emit('logged-in','loggedin')
      },

      mounted() {
        //
      }
    }
}
</script>