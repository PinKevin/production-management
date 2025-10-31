<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '../ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../ui/card';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { baseUrl } from '@/api/baseUrl';
import { setToken, setUser } from '@/helper/authHelper';

const email = ref('');
const password = ref('');

const emailError = ref('');
const passwordError = ref('');
const commonError = ref('');

const isLoading = ref(false);
const router = useRouter();

const handleLogin = async () => {
  isLoading.value = true;
  commonError.value = '';
  emailError.value = '';
  passwordError.value = '';

  try {
    const response = await axios.post(`${baseUrl}/login`, {
      email: email.value,
      password: password.value,
    });

    setToken(response.data.data.token);
    setUser(response.data.data.user);

    router.push('/production-plans');
  } catch (error: any) {
    const status = error.response.status;
    const data = error.response.data;

    if (error.response) {
      if (status === 401) {
        commonError.value = data?.message || 'Wrong email or password';
      } else if (status === 422) {
        emailError.value = data.data.email?.[0] || '';
        passwordError.value = data.data.password?.[0] || '';

        if (!emailError.value && !passwordError.value) {
          commonError.value = data?.message || 'Invalid data';
        }
      } else {
        commonError.value = 'Server error';
      }
    } else if (error.request) {
      commonError.value = 'Cannot connect to server';
    } else {
      commonError.value = 'Something happened';
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="flex flex-col gap-6">
    <Card>
      <CardHeader class="text-center">
        <CardTitle class="text-xl"> Welcome back </CardTitle>
        <CardDescription> Login with your email and password </CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit.prevent="handleLogin">
          <div class="grid gap-6">
            <div class="grid gap-6">
              <div class="grid gap-2">
                <Label html-for="email">Email</Label>
                <Input
                  id="email"
                  type="email"
                  placeholder="m@example.com"
                  v-model="email"
                  autocomplete="email"
                />
                <div v-if="emailError" class="text-sm text-red-600 mt-1">
                  {{ emailError }}
                </div>
              </div>
              <div class="grid gap-2">
                <Label html-for="password">Password</Label>
                <Input
                  id="password"
                  type="password"
                  v-model="password"
                  autocomplete="current-password"
                />
                <div v-if="passwordError" class="text-sm text-red-600 mt-1">
                  {{ passwordError }}
                </div>
              </div>

              <div v-if="commonError" class="text-sm text-red-600 text-center mt-1">
                {{ commonError }}
              </div>

              <Button type="submit" class="w-full" :disabled="isLoading">
                {{ isLoading ? 'Loading...' : 'Login' }}
              </Button>
            </div>
          </div>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
