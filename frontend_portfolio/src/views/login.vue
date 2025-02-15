<template>
  <div class="pt-16">
      <div class="flex flex-col items-center justify-center gap-4" v-if="!verifyAction">
          <h2 class="text-2xl font-semibold text-gray-800">Login</h2>
          <div>
              <input type="email" placeholder="Email" v-model="credentials.email" 
              class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-200" :class="{ 'border-red-500 text-red-500': v$.email.$errors.length }">
              <div class="input-errors" v-for="error of v$.email.$errors" :key="error.$uid">
                  <div class="error-msg">{{ error.$message }}</div>
              </div>
              <div v-if=" errors && errors.email">
                <div class="input-errors" v-for="error of errors.email" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
          </div>
          <div>
              <input type="password" placeholder="Password" v-model="credentials.password" class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-200" :class="{ 'border-red-500 text-red-500': v$.password.$errors.length }">
              <div class="input-errors" v-for="error of v$.password.$errors" :key="error.$uid">
                  <div class="error-msg">{{ error.$message }}</div>
              </div>
              <div v-if="errors && errors.password">
                <div class="input-errors" v-for="error of errors.password" :key="error">
                      <div class="error-msg">{{ error }}</div>
                </div>
            </div>
          </div>
          <div class="input-errors">
              <div class="error-msg">
                  {{ invalidError }}
                </div>
            </div>
          <div class="text-sm">
              You don`t have an account? <button class="text-blue-500 hover:underline cursor-pointer" @click="goRegister">Register</button>
          </div>

          <div>
              <button @click="handleLogin" class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer">Login</button>
          </div>
      </div>
      <div class="flex flex-col items-center justify-center gap-4" v-else>
          <h2 class="text-2xl font-semibold text-gray-800">Verify</h2>
          <div>
              <input type="number" placeholder="You Code" v-model="credentials.code" class="appearance-none px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-200" :class="{ 'border-red-500 text-red-500': v$.code.$errors.length }">
              <div class="input-errors" v-for="error of v$.code.$errors" :key="error.$uid">
                  <div class="error-msg">{{ error.$message }}</div>
              </div>
          </div>
          <div class="text-sm">
              You don`t have an account? <button class="text-blue-500 hover:underline cursor-pointer" @click="goRegister">Register</button>
          </div>
          <div>
              <button class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="handleVerify">Verify</button>
          </div>
      </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useVuelidate } from '@vuelidate/core'
import { required, email, numeric ,minLength} from '@vuelidate/validators'
import api from '@/api';

const verifyAction = ref(false);
const router = useRouter();
const credentials = reactive({
    email: '',
    password: '',
    code: '',
});

const rules = {
  email: { required, email },
  password: { required , minLength: minLength(6) },
  code: { numeric }
}

const v$ = useVuelidate(rules, credentials)

const errors  = ref({});
let invalidError = ref('');
const goRegister = () => {
    router.push({name: 'register'});
};
const handleLogin = async () => {
  v$.value.$touch();
  if(!v$.value.$invalid) {
      await api.post('/user/login', credentials)
      .then((response) => {
        localStorage.setItem('token', response.data.access_token);
        console.log(response);
        checkVerify();
      })
      .catch((error) => {
          console.log(error);
          errors.value = error.response.data.errors;
          if(error.response.data.error){
            invalidError.value = error.response.data.error;
          }
        });
      }
};
const checkVerify =async () => {
  await api.post('/user/verify/check',credentials,{
    headers:{
        Authorization: `Bearer ${localStorage.getItem('token')}`
    }
   }).then((res)=>{
    console.log(res.data)
    router.push({name:'dashboard'})
  }).catch((error)=>{
    verifyAction.value = true;
    console.log(error)
  })
}

const handleVerify = async () => {
    v$.value.$touch();
    if(!v$.value.$invalid){
      await api.post('/user/verify',credentials,{
        headers:{
              Authorization: `Bearer ${localStorage.getItem('token')}`
          }  
        }
      )
      .then((response) => {
          localStorage.setItem('token', response.data.access_token);
          console.log(response);
          router.push({name:'dashboard'})
      })
      .catch((error) => {
          console.log(error);
          errors.value = error.response.data.errors;
      });
    }
}
</script>

<style scoped>
.input-errors {
    color: red;
    font-size: 12px;
    margin-left: 5px;
}
</style>