<script setup>
import {  RouterView } from 'vue-router'
import router from './router';

const logout = () => {
  localStorage.removeItem('token');
  router.push({name: 'login'});
}
</script>

<template>
  <div class="bg-slate-100 min-h-screen">
    <div class="bg-gray-200" v-if="$route.name !== 'login' && $route.name !== 'register'">
      <nav class="flex gap-4 p-4 justify-between container mx-auto">
        <div class="flex gap-4 items-center">
          <RouterLink :to="{name: 'dashboard'}" :class="{ 'scale-110': $route.name === 'dashboard' }" class="transition duration-300 font-bold bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700" >Dashboard</RouterLink>
          <RouterLink :to="{name: 'skills'}" :class="{ 'scale-110': $route.name === 'skills' }" class="transition duration-300 font-bold bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700" >Skills</RouterLink>
          <RouterLink :to="{name: 'projects'}" :class="{ 'scale-110': $route.name === 'projects' }" class="transition duration-300 font-bold bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700" >Projects</RouterLink>
        </div>
        <div class="flex gap-4 items-center">
          <button @click="logout" class="transition duration-300 font-bold bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 cursor-pointer" >Logout</button>
        </div>
      </nav>
    </div>
    <div class="container mx-auto">
      <RouterView />
    </div>
  </div>
</template>

