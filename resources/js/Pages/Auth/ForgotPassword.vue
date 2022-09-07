<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />
<div class="login_section forget_password">
    <div class="left_div_inputs">
        <div class="img_logo">
            <a class="" href="/login"><img src="images/logo.png"></a>
            </div>
    <JetAuthenticationCard class="forget_div_class">
        <!-- <template #logo>
            <JetAuthenticationCardLogo />
        </template> -->

        <div class="">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <JetValidationErrors class="mb-4" />

       <div class="">
        <form @submit.prevent="submit">
            <div class="inputs_div">
                <JetLabel for="email" value="Email" class="me-2" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full p-2"
                    required
                    autofocus
                />
            </div>

            <div class="flex items-center justify-end mt-4 btn_reset">
                <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </JetButton>
            </div>
        </form>
        </div>
    </JetAuthenticationCard>
    </div>
    <div class="right_div_imgs">
            <div class="img_rights">
               <a href="#">
               <img src="images/login_1.jpg" class="img-fluid">
               </a>
            </div>
         </div>
    </div>
</template>
