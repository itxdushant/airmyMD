<script setup>
   import { Link } from '@inertiajs/inertia-vue3'
</script>
<template>
   <div class="left_bar airmymd_nav">
      <div class="brand_log">
         <a href="/"><img src="/images/logo.png"></a>
      </div>
      <ul class="left_links">
         <li> <Link href="/doctors"><img src="/images/patients.png">Doctors</Link></li>
         <!-- <li> <Link href="/profile"><img src="/images/profile.png">Profile</Link></li>
         <li> <Link href="/appointments"><img src="/images/calender.png">My Appointments</Link> </li>
         <li> <Link href="/appointment-requests"><img src="/images/calender.png">Appointments Request</Link> </li>
         <li> <Link href="/appointment-history"><img src="/images/calender.png">Appointments History</Link> </li>
         <li> <Link href="/availability"><img src="/images/calender.png">Availability</Link></li>
         <li> <Link href="/patients"><img src="/images/patients.png">Patients</Link> </li>
         <li> <Link href="/transactions"><img src="/images/list.png">Transactions</Link></li>
         <li> <a href="/chat"><img src="/images/message.png">Messages</a></li>
         <li> <Link href="/account"><img src="/images/profile_2.png">Account</Link></li> -->
      </ul>
      <ul class="log_out left_links">
         <li> <a href="/logout" class="btn_logout"><img src="/images/logout.png">Log out</a></li>
      </ul>
   </div>
</template>