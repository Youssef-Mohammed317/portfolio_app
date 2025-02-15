<template>
    <h2 class="mt-6 text-2xl font-semibold text-gray-800">Skills</h2>
    <div class="">
        <div class="flex justify-end items-center">
            <button class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="openAdding = true">Add Skill</button>
        </div>
    </div>
    <div class="mt-10 text-center">
        <div class="grid grid-cols-3 my-2 px-4 py-2 bg-gray-50 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20">
            <h2 class="text-lg font-semibold text-gray-800">Name</h2>
            <h2 class="text-lg font-semibold text-gray-800">Image</h2>
            <h2 class="text-lg font-semibold text-gray-800">Action</h2>
        </div>
        <div class="grid grid-cols-3 my-2 px-4 py-2 bg-white rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20" v-for="skill of skills" :key="skill.id">
            <h2 class="text-lg font-semibold text-gray-800 flex justify-center items-center">{{ skill.name }}</h2>
            <div class="flex justify-center items-center">
                <img :src="skill.image" class="w-16 h-16 object-cover rounded-full" alt="">
            </div>
            <div class="flex justify-center items-center gap-4">
                <button class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="editSkill(skill)">Edit</button>
                <button class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="openDeleteing(skill)">Delete</button>
            </div>
        </div>
    </div>
    <div class="fixed flex justify-center items-center top-0 right-0 w-full h-screen bg-gray-200 z-50" v-if="openAdding">
        <div class="flex flex-col items-center justify-center gap-4 w-[450px] max-w-full opacity-100 p-6 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 ">
            <h2 class="text-2xl font-semibold text-gray-800">Add New Skill</h2>
            <div class="w-full">
                <input type="text" placeholder="Name" v-model="newSkill.name" class="w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">
                <div class="input-errors" v-for="error of v$.name.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.name" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="w-full">
                <div class="flex w-full">
                    <button class="w-50 px-4 py-2 text-white bg-gray-800 rounded-l-md hover:bg-gray-700 cursor-pointer" onclick="document.getElementById('image').click()">Upload Image</button>
                    <input type="file" accept="image/*" @change="newSkill.image = $event.target.files[0]" placeholder="Image" id="image" class="w-65 cursor-pointer w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-200">
                </div>
                <div class="input-errors" v-for="error of v$.image.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.image" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="flex gap-4 justify-between items-center w-full px-10">
                <button class="px-6 py-2 text-gray-800 border-gray-800 rounded hover:bg-gray-300 cursor-pointer" @click="openAdding = false">Cancel</button>
                <button class="px-6 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="addSkill">Add Skill</button>
            </div>
        </div>
    </div>
    <div class="fixed flex justify-center items-center top-0 right-0 w-full h-screen bg-gray-200 z-50" v-if="openEditing">
        <div class="flex flex-col items-center justify-center gap-4 w-[450px] max-w-full opacity-100 p-6 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 ">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Skill</h2>
            <div class="w-full">
                <input type="text" placeholder="Name" v-model="editedSkill.name" class="w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">
                <div class="input-errors" v-for="error of v2$.name.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.name" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class=" w-full">
                <div class="flex w-full">
                    <button class="w-40 px-4 py-2 text-white bg-gray-800 rounded-l-md hover:bg-gray-700 cursor-pointer" onclick="document.getElementById('image').click()">Change Image</button>
                    <input type="file" accept="image/*" @change="editedSkill.image = $event.target.files[0]" placeholder="Image" id="image" class="cursor-pointer w-65 mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-200">
                </div>
                <div class="input-errors" v-for="error of errors.image" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="flex gap-4 justify-between items-center w-full px-10">
                <button class="px-6 py-2 text-gray-800 border-gray-800 rounded hover:bg-gray-300 cursor-pointer" @click="openEditing = false">Cancel</button>
                <button class="px-6 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="updateSkill">Update Skill</button>
            </div>
        </div>
    </div>
    <div class="fixed flex justify-center items-center top-0 right-0 w-full h-screen bg-gray-200 z-50" v-if="openDelete">
        <div class="flex flex-col items-center justify-center gap-4 w-[450px] max-w-full opacity-100 p-6 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 ">
            <h2 class="text-2xl font-semibold text-gray-800">Delete Skill</h2>
            <div class="">
                <h2 class="text-lg font-semibold text-gray-800">Are you sure you want to delete this skill?</h2>
            </div>
            <div class="">
                Name: {{ deletingSkill.name }}
            </div>
            <div class="flex gap-4 justify-between items-center w-full px-10">
                <button class="px-6 py-2 text-gray-800 border-gray-800 rounded hover:bg-gray-300 cursor-pointer" @click="openDelete = false">Cancel</button>
                <button class="px-6 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="deleteSkill">Delete Skill</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import api from '@/api';
import { onMounted, reactive, ref } from 'vue';
import { useVuelidate } from '@vuelidate/core'
import { required ,minLength} from '@vuelidate/validators'

const skills = ref([]);
const newSkill = reactive({
    name: '',
    image: ''
});

const rules = {
    name: {required, minLength:minLength(3)},
    image: {required}
}

const errors = ref({});

const v$ = useVuelidate(rules, newSkill)

const editedSkill = reactive({
    name: '',
    image: '',
    id: ''
});

const rules2 = {
    name: {required, minLength:minLength(3)},
}

const v2$ = useVuelidate(rules2, editedSkill)

const openAdding = ref(false);

const openEditing = ref(false);

onMounted(() => {
    getSkills();
})

const addSkill = async () => {
    v$.value.$touch();
    if(!v$.value.$invalid){   
        await api.post('/skills', newSkill,{
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,    
            }
        }).then((res)=>{
            console.log(res.data)
            openAdding.value = false
            getSkills();
            newSkill.name = '';
            newSkill.image = '';
            v$.value.$reset();
        }).catch((error)=>{
            console.log(error)
            errors.value = error.response.data.errors;
        })
    }
}

const getSkills = async () => {
  await api.get('/skills',{
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    }
  }).then((res)=>{
    console.log(res.data)
    skills.value = res.data.data
  }).catch((error)=>{
    console.log(error)
  })  
}

const editSkill = (skill) => {
    openEditing.value = true;
    editedSkill.name = skill.name;
    editedSkill.image = null
    editedSkill.id = skill.id;
}

const updateSkill = async () => {
    v2$.value.$touch();
    if(!v2$.value.$invalid){
        await api.post(`/skills/${editedSkill.id}`, editedSkill, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            }
        }).then((res)=>{
            console.log(res.data)
            openEditing.value = false;
            getSkills();
        }).catch((error)=>{
            console.log(error)
        })
    }
}

const deletingSkill = reactive({
    id: '',
    name: ''
})
const openDelete = ref(false);
const openDeleteing = (skill) => {
    openDelete.value = true;
    deletingSkill.id = skill.id
    deletingSkill.name = skill.name
}

const deleteSkill = async () => {
    await api.delete(`/skills/${deletingSkill.id}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        }
    }).then((res)=>{
        console.log(res.data)
        openDelete.value = false
        getSkills();
    }).catch((error)=>{
        console.log(error)
    })
}
</script>

<style scoped>
.input-errors {
    color: red;
    font-size: 12px;
    margin-left: 5px;
}
</style>