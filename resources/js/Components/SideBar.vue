<script setup>
   import { Link } from '@inertiajs/inertia-vue3'
</script>
<template>
   <div class="left_bar airmymd_nav">
      <div class="brand_log">
         <a href="/"><img src="/images/logo.png"></a>
      </div>
      <ul class="left_links">
         <li :class="(this.dashboardActive)? 'active' :( (urlsigment)? ((urlsigment.includes('dashboard'))? 'active': ''): '')  "> <Link href="/dashboard"><img src="/images/dashboard.png">Dashboard</Link></li>
         <li :class="(urlsigment)? ((urlsigment.includes('profile'))? 'active': ''): ''"> <Link href="/profile"><img src="/images/profile.png">Profile</Link></li>
         <li :class="(urlsigment)? ((urlsigment.includes('appointments'))? 'active': ''): ''"> <Link href="/appointments"><img src="/images/calender.png">My Appointments</Link> </li>
         <li :class="(urlsigment)? ((urlsigment.includes('appointment-requests'))? 'active': ''): ''"> <Link href="/appointment-requests"><img src="/images/calender.png">Appointments Request</Link> </li>
         <li :class="(urlsigment)? ((urlsigment.includes('appointment-history'))? 'active': ''): ''"> <Link href="/appointment-history"><img src="/images/calender.png">Appointments History</Link> </li>
         <li :class="(urlsigment)? ((urlsigment.includes('availability'))? 'active': ''): ''"> <Link href="/availability"><img src="/images/calender.png">Availability</Link></li>
         <li :class="(urlsigment)? ((urlsigment.includes('patients'))? 'active': ''): ''"> <Link href="/patients"><img src="/images/patients.png">Patients</Link> </li>
         <li :class="(urlsigment)? ((urlsigment.includes('transactions'))? 'active': ''): ''"> <Link href="/transactions"><img src="/images/list.png">Transactions</Link></li>
         <li :class="(urlsigment)? ((urlsigment.includes('chat'))? 'active': ''): ''"> <a href="/chat"><img src="/images/message.png">Messages</a></li>
         <li :class="(urlsigment)? ((urlsigment.includes('reviews'))? 'active': ''): ''"> <Link href="/reviews"><img src="/images/message.png">Reviews</Link></li>
         <li :class="(urlsigment)? ((urlsigment.includes('account'))? 'active': ''): ''"> <Link href="/account"><img src="/images/profile_2.png">Account</Link></li>
      </ul>
      <ul class="log_out left_links">
         <li> <a href="/logout" class="btn_logout"><img src="/images/logout.png">Log out</a></li>
      </ul>
   </div>
</template>

<script>
   export default {
      components: {
      },
      props: [],
      data() {
         return { 
            urlsigment: null,
            dashboardActive:null
         }
      },
      methods: {  
         
      },
      mounted() { 
         const pathname = window.location.pathname;
         this.urlsigment = pathname.split('/');
         if(!this.urlsigment[1]){
            this.dashboardActive = "active";
         }

      }
    }
</script>