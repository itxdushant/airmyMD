<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';

const props = defineProps({
    status: String,
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>
<style scoped>
   
</style>
<template>
    <Head title="Email Verification" />
    <div class="login_section">
        <div class="left_div_inputs">
            <div class="img_logo">
                <a class="" href="#"><img src="/images/logo.png"></a> 
            </div>
            <div class="account_div">

                <div class="mb-4 text-sm text-gray-600">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </div>

                <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
                    A new verification link has been sent to the email address you provided during registration.
                </div>

                <form @submit.prevent="submit">
                    <div class="mt-4 flex items-center justify-between btn_reset">
                        <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Resend Verification Email
                        </JetButton>

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900"
                        >
                            Log Out
                        </Link>
                    </div>
                </form>
            </div>
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
