<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

</script>
<style scoped>
    .login_section .mb-4 {
        display: none;
    }
    .login_section .mb-4.font-medium.text-sm.text-green-600 {
        display: block !important;
        color: red !important;
    }
</style>
<template>
    <Head title="Log in" />
    <div class="login_section">
         <div class="left_div_inputs">
            <div class="img_logo">
               <a class="" href="#"><img src="/images/logo.png"></a> 
            </div>
            <div class="account_div">
               <!-- <p class="p_text">Start for free</p> -->
               <h3 class="h3_tittle">Welcome Back</h3>
               <JetValidationErrors class="mb-4" />
                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <form @submit.prevent="submit">
                   <div class="inputs_div">
                      <input type="email" v-model="form.email" id="email" required autofocus placeholder="Email">
                      <input type="password" placeholder="Password"  id="password" v-model="form.password" required autocomplete="current-password">
                      <button class="btn_sign" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Sign In</button>
                      <a href="/forgot-password" class="mt-3 text-center d-block">Forgot Password</a>
                   </div>
                </form>
               <p class="alerdy_account">Don't have an account? <Link href="/register"> Sign up</Link> </p>
            </div>
            <p class="privacy_text"><a href="/privacy-policy" class="">Privacy Policy</a> and <a href="/terms">Terms of Service</a></p>
         </div>
         <div class="right_div_imgs">
            <div class="img_rights">
               <a href="#">
               <img src="/images/login_1.jpg" class="img-fluid">
               </a>
            </div>
         </div>
    </div>
</template>
