import Vue from 'vue'
import Router from 'vue-router'
// import HelloWorld from '@/components/HelloWorld'
// import Home from '@/components/Home'
import Login from '@/components/Login'
// import Register from '@/components/Register'
import Search from '@/components/Search'
import NotFound from '@/components/NotFound'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      redirect: '/login'
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    // {
    //   path: '/register',
    //   name: 'Register',
    //   component: Register
    // },
    {
      path: '/search',
      name: 'Search',
      component: Search
    },
    { path: '*', component: NotFound }
  ]
})
