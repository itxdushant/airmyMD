<script setup>
   import { Link } from '@inertiajs/inertia-vue3'
</script>
<template>
   <div class="dropdown ">
      <div class=" dropdown-toggle name_1" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
         <img v-if="!$page.props.user.profile_photo_path" src="/images/i_1.png">
         <img v-if="$page.props.user.profile_photo_path" width="32" style="border-radius: 50px;" height="32" :src="'/'+$page.props.user.profile_photo_path">
         {{$page.props.user.name}}
      </div>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
         <li><a class="dropdown-item" href="/logout">logout</a></li>
      </ul>
   </div>
</template>