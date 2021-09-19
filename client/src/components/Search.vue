<template>
  <div class="container">
    <div class="jumbotron mt-5">
      <div class="col-8 mx-auto">
        <h2 class="text-center">Companies</h2>
        <p>click on company name to mark/unmark as favourite</p>
      </div>
      
      <div class="row">
        <div class="col-4 offset-4">
          <button class="btn btn-primary" v-on:click="getCompanies()">All</button> 
          <button class="btn btn-primary" v-on:click="getFavourite()">Favourite</button>
        </div>
        <div class="col-4">
        <input type="text" placeholder="Search Company Name" class="form-control" name="search" v-model="search" @input="debouncedOnChange()">
    </div>
      </div>
      
      <div class="row">

      <table class="table">
        <tbody>
          <tr>
            <!-- <td></td> -->
            <td>ID</td>
            <td>Company</td>
            <td>Phone</td>
            <td>Address</td>
          </tr>
          <tr v-for="company in companies" :key="company.id">
            <!-- <td><input type="checkbox" v-on:click="addFavourite(company.id)"></td> -->
            <td>{{ company.id }}</td>
            <td v-on:click="addFavourite(company.id)">{{ company.company_name }}</td>
            <td>{{ company.phone_number }}</td>
            <td>{{ company.address }}</td>
          </tr>
        </tbody>
      </table>

      </div>

    </div>
  </div>
</template>


<script>
import axios from 'axios'
import router from '../router'
import _debounce from 'lodash.debounce';

export default{
    data() {
      this.getCompanies(this.search = '').then(res => {
        // console.log(res);
        // this.wholename = res.user.name
        this.companies = res.data
        return res
      })
      
      return {
        search: '',
        companies: []
      }
    },
    methods: {
      getCompanies(search = '') {
        return axios.get('http://localhost:8000/api/companies/search/'+this.search, {
          headers:{
            Authorization: 'Bearer ' + localStorage.usertoken
          }
        })
        .then(res => {
          console.log(res);
          this.companies = res.data.data
          return res.data
        })
        .catch(err =>{
          console.log(err)
          localStorage.removeItem('usertoken');
          router.push({name: 'Login'})
        })
      },
      addFavourite(id) {
        return axios.post('http://localhost:8000/api/companies/add-favourite', 
        {
          company_id: id
        },
        {
          headers:{
            Authorization: 'Bearer ' + localStorage.usertoken
          }
        })
        .then(res => {
          console.log(res);
          if (res.data.message == 'Company is removed from Favourite List') {
            this.companies = res.data.data
          }
        })
        .catch(err =>{
          console.log(err)
        })
      },
      getFavourite() {
        return axios.get('http://localhost:8000/api/companies/favourite-list', {
          headers:{
            Authorization: 'Bearer ' + localStorage.usertoken
          }
        })
        .then(res => {
          console.log(res);
          this.companies = res.data.data
          return res.data
        })
        .catch(err =>{
          console.log(err)
          localStorage.removeItem('usertoken');
          router.push({name: 'Login'})
        })
      },
    },
    mounted() {
      // 
    },
    computed: {
      debouncedOnChange () {
        return _debounce(function() { this.getCompanies(this.search) }, 500);
      }
    }
}
</script>