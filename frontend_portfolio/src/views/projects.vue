<template>
    <h2 class="mt-6 text-2xl font-semibold text-gray-800">Projects</h2>
    <div class="">
        <div class="flex justify-end items-center">
            <button class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="openAdding = true">Add Project</button>
        </div>
    </div>
    <div class="mt-10 text-center">
        <div class="grid grid-cols-5 my-2 px-4 py-2 bg-gray-50 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20">
            <h2 class="text-lg font-semibold text-gray-800">Name</h2>
            <h2 class="text-lg font-semibold text-gray-800">Image</h2>
            <h2 class="text-lg font-semibold text-gray-800">Skill</h2>
            <h2 class="text-lg font-semibold text-gray-800">Link</h2>
            <h2 class="text-lg font-semibold text-gray-800">Action</h2>
        </div>
        <div class="grid grid-cols-5 my-2 px-4 py-2 bg-white rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20" v-for="project of projects" :key="project.id">
            <h2 class="text-lg font-semibold text-gray-800 flex justify-center items-center">{{ project.name }}</h2>
            <div class="flex justify-center items-center">
                <img :src="project.image" class="w-16 h-16 object-cover rounded-full" alt="">
            </div>
            <h2 class="text-lg font-semibold text-gray-800 flex justify-center items-center">{{ project.skill.name }}</h2>
            <h2 class="text-lg font-semibold text-gray-800 flex justify-center items-center">{{ project.project_url ? project.project_url : 'No Link' }}</h2>
            <div class="flex justify-center items-center gap-4">
                <button class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="editProject(project)">Edit</button>
                <button class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="openDeleteing(project)">Delete</button>
            </div>
        </div>
    </div>
     <div class="fixed flex justify-center items-center top-0 right-0 w-full h-screen bg-gray-200 z-50" v-if="openAdding">
        <div class="flex flex-col items-center justify-center gap-4 w-[450px] max-w-full opacity-100 p-6 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 ">
            <h2 class="text-2xl font-semibold text-gray-800">Add New Project</h2>
            <div class="w-full">
                <input type="text" placeholder="Name" v-model="newProject.name" class="w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">
                <div class="input-errors" v-for="error of v$.name.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.name" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="w-full">
                <input type="text" placeholder="Project URL" v-model="newProject.project_url" class="w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">
                <div class="input-errors" v-for="error of v$.project_url.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.project_url" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="w-full">
                <select v-model="newProject.skill_id" class="w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <option value="" disabled selected>Select Skill</option>
                    <option v-for="skill of skills" :key="skill.id" :value="skill.id">{{ skill.name }}</option>
                </select>
                <div class="input-errors" v-for="error of v$.skill_id.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.skill_id" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="w-full">
                <div class="flex w-full">
                    <button class="w-50 px-4 py-2 text-white bg-gray-800 rounded-l-md hover:bg-gray-700 cursor-pointer" onclick="document.getElementById('image').click()">Upload Image</button>
                    <input type="file" accept="image/*" @change="newProject.image = $event.target.files[0]" placeholder="Image" id="image" class="w-65 cursor-pointer w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-200">
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
                <button class="px-6 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="addProject">Add Project</button>
            </div>
        </div>
    </div>
   <div class="fixed flex justify-center items-center top-0 right-0 w-full h-screen bg-gray-200 z-50" v-if="openEditing">
        <div class="flex flex-col items-center justify-center gap-4 w-[450px] max-w-full opacity-100 p-6 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 ">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Project</h2>
            <div class="w-full">
                <input type="text" placeholder="Name" v-model="editedProject.name" class="w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">
                <div class="input-errors" v-for="error of v2$.name.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.name" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="w-full">
                <input type="text" placeholder="Project URL" v-model="editedProject.project_url" class="w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">
                <div class="input-errors" v-for="error of v2$.project_url.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.project_url" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="w-full">
                <select v-model="editedProject.skill_id" class="w-full mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <!-- <option value="" disabled selected>Select Skill</option> -->
                    <option v-for="skill of skills" :key="skill.id" :value="skill.id" :selected="skill.id == editedProject.skill_id">{{ skill.name }}</option>
                </select>
                <div class="input-errors" v-for="error of v$.skill_id.$errors" :key="error.$uid">
                    <div class="error-msg">{{ error.$message }}</div>
                </div>
                <div class="input-errors" v-for="error of errors.skill_id" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class=" w-full">
                <div class="flex w-full">
                    <button class="w-40 px-4 py-2 text-white bg-gray-800 rounded-l-md hover:bg-gray-700 cursor-pointer" onclick="document.getElementById('image').click()">Change Image</button>
                    <input type="file" accept="image/*" @change="editedProject.image = $event.target.files[0]" placeholder="Image" id="image" class="cursor-pointer w-65 mx-auto px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-200">
                </div>
                <div class="input-errors" v-for="error of errors.image" :key="error">
                    <div class="error-msg">{{ error }}</div>
                </div>
            </div>
            <div class="flex gap-4 justify-between items-center w-full px-10">
                <button class="px-6 py-2 text-gray-800 border-gray-800 rounded hover:bg-gray-300 cursor-pointer" @click="openEditing = false">Cancel</button>
                <button class="px-6 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="updateProject">Update Project</button>
            </div>
        </div>
    </div>
    <div class="fixed flex justify-center items-center top-0 right-0 w-full h-screen bg-gray-200 z-50" v-if="openDelete">
        <div class="flex flex-col items-center justify-center gap-4 w-[450px] max-w-full opacity-100 p-6 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 ">
            <h2 class="text-2xl font-semibold text-gray-800">Delete Project</h2>
            <div class="">
                <h2 class="text-lg font-semibold text-gray-800">Are you sure you want to delete this project?</h2>
            </div>
            <div class="">
                Name: {{ deletingProject.name }}
            </div>
            <div class="flex gap-4 justify-between items-center w-full px-10">
                <button class="px-6 py-2 text-gray-800 border-gray-800 rounded hover:bg-gray-300 cursor-pointer" @click="openDelete = false">Cancel</button>
                <button class="px-6 py-2 text-white bg-gray-800 rounded hover:bg-gray-700 cursor-pointer" @click="deleteProject">Delete Project</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import api from '@/api';
import { onMounted, reactive, ref } from 'vue';
import { useVuelidate } from '@vuelidate/core'
import { required ,minLength, numeric, url} from '@vuelidate/validators'

const skills = ref([]);
const projects = ref([]);
const newProject = reactive({
    name: '',
    image: '',
    project_url: '',
    skill_id: ''
});

const rules = {
    name: {required, minLength:minLength(3)},
    image: {required},
    project_url: {url},
    skill_id: {required, numeric}
}

const errors = ref({});

const v$ = useVuelidate(rules, newProject)

const editedProject = reactive({
    name: '',
    image: '',
    id: '',
    project_url: '',
    skill_id: ''
});

const rules2 = {
    name: {required, minLength:minLength(3)},
    project_url: {url},
    skill_id: {required, numeric}
}

const v2$ = useVuelidate(rules2, editedProject)

const openAdding = ref(false);

const openEditing = ref(false);

onMounted(() => {
    getProjects();
    getSkills();
})
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

const addProject = async () => {
    v$.value.$touch();
    if(!v$.value.$invalid){   
        await api.post('/projects', newProject,{
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,    
            }
        }).then((res)=>{
            console.log(res.data)
            openAdding.value = false
            getProjects();
            newProject.name = '';
            newProject.image = '';
            newProject.project_url = '';
            newProject.skill_id = '';
            v$.value.$reset();
        }).catch((error)=>{
            console.log(error)
            errors.value = error.response.data.errors;
        })
    }
}

const getProjects = async () => {
  await api.get('/projects',{
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    }
  }).then((res)=>{
    console.log(res.data)
    projects.value = res.data.data
  }).catch((error)=>{
    console.log(error)
  })  
}

const editProject = (project) => {
    openEditing.value = true;
    editedProject.name = project.name;
    editedProject.image = null
    editedProject.id = project.id;
    editedProject.project_url = project.project_url;
    editedProject.skill_id = project.skill_id;
}

const updateProject = async () => {
    v2$.value.$touch();
    if(!v2$.value.$invalid){
        await api.post(`/projects/${editedProject.id}`, editedProject, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            }
        }).then((res)=>{
            console.log(res.data)
            openEditing.value = false;
            getProjects();
        }).catch((error)=>{
            console.log(error)
        })
    }
}

const deletingProject = reactive({
    id: '',
    name: '',
})
const openDelete = ref(false);
const openDeleteing = (project) => {
    openDelete.value = true;
    deletingProject.id = project.id
    deletingProject.name = project.name
}

const deleteProject = async () => {
    await api.delete(`/projects/${deletingProject.id}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        }
    }).then((res)=>{
        console.log(res.data)
        openDelete.value = false
        getProjects();
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