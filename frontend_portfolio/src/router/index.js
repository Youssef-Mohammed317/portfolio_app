import api from '@/api';
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/register.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/login.vue'),
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/dashboard.vue'),
    },
    {
      path: '/skills',
      name: 'skills',
      component: () => import('../views/skills.vue'),
    },
    {
      path: '/projects',
      name: 'projects',
      component: () => import('../views/projects.vue'),
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: () => import('../views/NotFound.vue'),
    }
  ],
})

router.beforeEach(async (to,from)=>{
  if(to.name == 'login' || to.name == 'register'){
    if(localStorage.getItem('token')){
      await checkUser();
      setTimeout(() => {
        return {
          name : 'dashboard'
        }
      }, 1000);
    }
    return true;
  }

  if(!localStorage.getItem('token')){
    localStorage.removeItem('token')
    return {
      name : 'login'
    }
  }

  await checkUser();
})

const checkUser = async () => {
  await api.get('/user',{
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }  
  }).then((res)=>{
    console.log('roting',res.data)
    if(res.data.code != null){
      localStorage.removeItem('token')
      return {
        name : 'login'
      }
    }
  })
  .catch((error) =>{
    console.log(error);
    localStorage.removeItem('token')
    return {
      name : 'login'
    }
  })
}

export default router
