<template>
    <div class="main_body">
      <AdminSideBar></AdminSideBar>
         <!-- left_bar end here -->
         <div class="right_section">
            <div class="mobile_header d-block d-lg-none">
               <div class="mobile_flex">
                  <a href="#"><img src="images/logo.png"></a>
                  <div class="toggle_icon">
                     <img class="toggle_menu" src="images/toggle.png">
                  </div>
               </div>
            </div>
            <div class="my_appointments_right transaction_section">
               <div class="top_bar_c">
                  <h3 class="h3_tittle">Transactions</h3>
                  <div class="right_top_input">
                     <!-- <div class="list_view_div">
                        <span class=" card_view pe-3">List View</span>
                        <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                        </label>
                        <span class="list_view">Card View </span>
                     </div> -->
                     <!-- <label for="search">
                        <input type="search" name="search">
                     </label> -->
                     <UserBar/>
                  </div>
               </div>
               <div class="search_filter_div">
                  <div class="search">
                     <input type="search" placeholder="Search by Name" @keyup="filterByKeyword($event)">
                  </div>
                  <!-- <div class="selct_input appt">
                     <select name="" id="">
                        <option value="">Appt. Type </option>
                        <option value="">Appt. Type 2</option>
                        <option value="">Appt. Type 3</option>
                        <option value="">Appt. Type 4</option>
                     </select>
                  </div>
                  <div class="selct_input visit_reason">
                     <select name="" id="">
                        <option value="">Visit Reason</option>
                        <option value="">Visit Reason 1</option>
                        <option value="">Visit Reason 2</option>
                        <option value="">Visit Reason 2</option>
                     </select>
                  </div> -->
                  <div class="selct_input date">
                     <input type="date" placeholder="FIlter by Date"  v-model="date" @change="changeHandle($event)" >
                  </div>
               </div>
               <div  class="modal-vue">
                  <div class="overlay" v-if="showModal" @click="showModal = false"></div>
                  <div class="vue-modal" v-if="showModal">
                     <button class="close" @click="showModal = false">x</button>
                     <p><b>Patient Name: </b> <span><a :href="'/patient/'+bookingDetails.booking.user_id">{{bookingDetails.booking.user.patient_profile.first_name}} {{bookingDetails.booking.user.patient_profile.last_name}}</a></span></p>
                     <p><b>Appointment Date: </b> <span>{{(bookingDetails)? moment(bookingDetails.booking.booking_date): ''}}</span></p>
                     <p><b>Appointment Time: </b> <span>{{bookingDetails.booking.booking_time}}</span></p>
                     <p><b>Payment Type: </b> <span>{{bookingDetails.booking.payment_type}}</span></p>
                     <p><b>Paid Amount: </b> <span>${{(bookingDetails.booking.fees/100) * 10}}</span></p>
                     <p><b>Payment Type: </b> <span>{{bookingDetails.booking.payment_type}}</span></p>
                     <p v-if="bookingDetails.booking.payment_type == 'insurance'"><b>Insurance: </b> 
                        <table class="table" v-if="bookingDetails.booking.patient_insurance.length > 0">
                           <thead>
                              <tr>
                                 <th>Image</th>
                                 <th>Insurance Provider</th>
                                 <th>Member ID</th>
                                 <th>Group Number</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr v-for="(insurance,index) in bookingDetails.booking.patient_insurance" :key="index">
                                 <td class="insurance-img">
                                    <a :href="insurance.image" target="_blank"><img :src="insurance.image"/></a>
                                 </td>
                                 <td>{{insurance.provider}}</td>
                                 <td>{{insurance.phone}}</td>
                                 <td>{{insurance.group_number}}</td>
                              </tr>
                           </tbody>
                        </table>
                        <span v-else> Insurance details not found.</span>
                     </p>
                  </div>
               </div>
               <div>                  
                  <div class="table_section">
                     <div v-if="success" class="alert alert-success text-dark font-weight-bold mt-4">
                        {{ success }}
                     </div>
                     <div v-if="error" class="alert alert-warning text-dark font-weight-bold mt-4">
                        {{ error }}
                     </div>
                     <table role="table">
                        <thead role="rowgroup">
                           <tr role="row">
                              <th role="columnheader">Patient Name</th>
                              <th role="columnheader">Appt. Date</th>
                              <th role="columnheader">Total Payment</th>
                              <th role="columnheader">Action</th>
                           </tr>
                        </thead>
                        <tbody role="rowgroup" v-if="!loading">
                           <tr role="row" v-for="(doc,index) in bookings.data" :key="index">
                              <td role="cell">{{doc.booking.user.patient_profile.first_name}} {{doc.booking.user.patient_profile.last_name}}</td>
                              <td role="cell">{{moment(doc.booking.booking_date)}}</td>
                              <td role="cell">${{(doc.booking.fees/100) * 10}}</td>
                              <td role="cell">
                                 <a href="#" class="btn_view_details" @click="openModal(doc)">View Details</a>
                              </td>
                           </tr>                        
                        </tbody>
                     </table>
                     <div class="row text-center" v-if="loading">
                        <div class="spinner-border" role="status" style="margin:50px auto;">
                        </div>
                     </div>
                     <div v-if="bookings.data.length == 0 && !loading">
                        <div class="alert alert-warning">
                           <strong>No Data Found.</strong>
                        </div>
                     </div>
                  </div>                 
               </div>
               <div class="pagination_div">
                  <Pagination :data="bookings" @pagination-change-page="list" />
               </div>
            </div>
            
         </div>
         <!-- right_section end here -->
      </div>
</template>
<script>
   import AdminSideBar from '@/Components/AdminSideBar'
   import UserBar from '@/Components/UserBar'
   import LaravelVuePagination from 'laravel-vue-pagination';
   import moment from 'moment';

   export default {
      components: {
         AdminSideBar,
         UserBar,
         'Pagination': LaravelVuePagination
      },
      props: [],
      data() {
         return { 
            loading: true,
            success: "",             
            error: "",    
            showModal:false,
            bookings: {
               data: []
            }, 
            page: 1,    
            bookingDetails: null,
            timer: null,
            keyword: "",
            date: "",
            
         }
      },
      methods: {  
         changeHandle(e){

            this.page = 1;
            if (this.timer) {
               clearTimeout(this.timer);
               this.timer = null;
            }
            this.timer = setTimeout(() => {
               this.getData();
            }, 800);
         },
         moment: function (date) {
            return moment(date).format('MMMM DD YYYY');
         },
         openModal(data) {
            console.log(data);
            this.bookingDetails = data;
            this.showModal = true;
         },
         closeModal() {
               this.showModal = null;
         },
         list(page = 1) {
            this.page = page;
            this.getData();
         },
         filterByKeyword(event) {               
            this.page = 1;
            this.keyword = event.target.value;
            if (this.timer) {
               clearTimeout(this.timer);
               this.timer = null;
            }
            this.timer = setTimeout(() => {
               this.getData();
            }, 800);            
         },    
         getData() {
            this.loading = true;
            axios.get(`/admin/transactions-list?page=${this.page}&keyword=${this.keyword}&date=${this.date}`).then(({data}) => {                     
               this.bookings = data
               console.log(data, "sdfsdfsdfsfsfs");
               this.loading = false;
            }).catch(({ response })=>{
               this.loading = false;
               console.error(response)
            })
         },
      },
      mounted() { 
         this.list() 
      }
    }
</script>