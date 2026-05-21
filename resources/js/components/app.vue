<template>
  <div class="max-w-6xl mx-auto mt-12 p-8 bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 transition-colors duration-300">
    
    <header class="mb-10 relative flex flex-col items-center">
      <button 
        @click="toggleTheme" 
        class="absolute right-0 top-0 p-3 rounded-full bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-yellow-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all shadow-sm focus:outline-none"
      >
        <Moon v-if="!isDark" class="w-5 h-5" />
        <Sun v-else class="w-5 h-5" />
      </button>

      <div class="bg-indigo-600 p-3 rounded-xl mb-4 shadow-lg shadow-indigo-200 dark:shadow-indigo-900/20">
        <Terminal class="text-white w-8 h-8" />
      </div>
      <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-400 mb-2">
        TASK MANAGER
      </h1>
    </header>

    <div class="flex flex-col gap-3 mb-8 bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-200 dark:border-slate-700 shadow-inner transition-colors">
      <div class="flex items-center pl-3 bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-600 p-1 focus-within:border-indigo-400 dark:focus-within:border-indigo-500 transition-all">
        <TerminalSquare class="text-gray-400 dark:text-slate-400 w-5 h-5 mr-3" />
        <input
          v-model="newTask.title"
          @keyup.enter="addTask"
          placeholder="Enter new task..."
          class="w-full bg-transparent py-2 text-gray-700 dark:text-slate-200 focus:outline-none text-md font-semibold"
        />
      </div>

      <div class="flex gap-2 items-center flex-wrap sm:flex-nowrap">
        
        <div class="flex-1 flex items-center bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-600 px-3 py-2">
          <select v-model="newTask.priority" class="w-full bg-transparent text-sm font-bold text-gray-600 dark:text-slate-300 outline-none cursor-pointer">
            <option value="low" class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200">Low Priority</option>
            <option value="medium" class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200">Medium Priority</option>
            <option value="high" class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200">High Priority</option>
          </select>
        </div>
        
        <div class="flex-1 flex items-center bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-600 px-3 py-2">
          <Tag class="text-gray-400 dark:text-slate-400 w-4 h-4 mr-2" />
          <select v-model="newTask.category" class="w-full bg-transparent text-sm font-bold text-gray-600 dark:text-slate-300 outline-none cursor-pointer">
            <option value="General" class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200">General</option>
            <option value="Frontend" class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200">Frontend</option>
            <option value="Backend" class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200">Backend</option>
            <option value="Design" class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200">Design</option>
            <option value="Personal" class="bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200">Personal</option>
          </select>
        </div>

        <div class="flex-1 flex items-center bg-white dark:bg-slate-800 rounded-lg border border-gray-100 dark:border-slate-600 px-3 py-2">
          <Calendar class="text-gray-400 dark:text-slate-400 w-4 h-4 mr-2" />
          <input type="date" v-model="newTask.due_date" :style="isDark ? 'color-scheme: dark;' : ''" class="w-full bg-transparent text-sm font-bold text-gray-600 dark:text-slate-300 outline-none cursor-pointer" />
        </div>

        <button
          @click="addTask"
          class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-all duration-200 active:scale-95 flex items-center gap-2 w-full sm:w-auto justify-center"
        >
          <Plus class="w-5 h-5" />
          <span>EXECUTE</span>
        </button>
      </div>
    </div>

    <div class="relative w-full mb-8">
      <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-slate-400 w-5 h-5" />
      <input
        v-model="searchQuery"
        placeholder="Search tasks..."
        class="w-full pl-10 pr-4 py-3 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:bg-slate-800 dark:text-white transition-shadow bg-white shadow-sm font-medium text-sm"
      />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      
      <div 
        class="flex flex-col bg-gray-50/50 dark:bg-slate-800/30 rounded-2xl p-4 border border-gray-200 dark:border-slate-700/50 min-h-[400px]"
        @drop="onDrop($event, 'todo')" 
        @dragenter.prevent 
        @dragover.prevent
      >
        <h2 class="text-sm font-extrabold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-4 flex items-center justify-between">
          <span>To Do</span>
          <span class="bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-slate-300 py-0.5 px-2.5 rounded-full text-xs">{{ getTasksByStatus('todo').length }}</span>
        </h2>
        <div class="flex-1 space-y-3">
          <div 
            v-for="task in getTasksByStatus('todo')" :key="task.id" 
            draggable="true" 
            @dragstart="startDrag($event, task)"
            class="group p-4 border border-gray-100 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 shadow-sm hover:shadow-md transition-all cursor-grab active:cursor-grabbing border-l-4"
            :class="{
              'border-l-red-500': task.priority === 'high',
              'border-l-yellow-400': task.priority === 'medium',
              'border-l-blue-400': task.priority === 'low'
            }"
          >
            <div class="flex justify-between items-start mb-2">
              <h3 class="text-md font-semibold text-gray-800 dark:text-slate-200">{{ task.title }}</h3>
              <button @click="deleteTask(task.id)" class="text-gray-300 hover:text-red-500 transition-colors">
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
            
            <div class="flex flex-wrap gap-2 mt-3 items-center">
              <span class="text-[9px] px-2 py-0.5 rounded-full uppercase font-black border" :class="getPriorityClass(task.priority)">{{ task.priority }}</span>
              <span v-if="task.category" class="flex items-center text-[9px] px-2 py-0.5 rounded-full uppercase font-black border bg-purple-50 text-purple-600 border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800"><Tag class="w-2.5 h-2.5 mr-1" />{{ task.category }}</span>
              
              <div class="flex items-center text-[9px] px-2 py-0.5 rounded-full uppercase font-black border bg-gray-50 text-gray-600 border-gray-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 relative group/date cursor-pointer">
                <Calendar class="w-2.5 h-2.5 mr-1 text-gray-400" />
                <input 
                  type="date" 
                  v-model="task.due_date" 
                  :style="isDark ? 'color-scheme: dark;' : ''"
                  @change="updateTaskFields(task, 'Date Updated!')"
                  class="bg-transparent border-none outline-none cursor-pointer text-[9px] uppercase font-black font-mono w-20 p-0"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div 
        class="flex flex-col bg-indigo-50/30 dark:bg-indigo-900/10 rounded-2xl p-4 border border-indigo-100 dark:border-indigo-900/30 min-h-[400px]"
        @drop="onDrop($event, 'in_progress')" 
        @dragenter.prevent 
        @dragover.prevent
      >
        <h2 class="text-sm font-extrabold text-indigo-500 dark:text-indigo-400 uppercase tracking-widest mb-4 flex items-center justify-between">
          <span>In Progress</span>
          <span class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 py-0.5 px-2.5 rounded-full text-xs">{{ getTasksByStatus('in_progress').length }}</span>
        </h2>
        <div class="flex-1 space-y-3">
          <div 
            v-for="task in getTasksByStatus('in_progress')" :key="task.id" 
            draggable="true" 
            @dragstart="startDrag($event, task)"
            class="group p-4 border border-indigo-100 dark:border-indigo-800/50 rounded-xl bg-white dark:bg-slate-800 shadow-sm hover:shadow-md transition-all cursor-grab active:cursor-grabbing border-l-4"
            :class="{
              'border-l-red-500': task.priority === 'high',
              'border-l-yellow-400': task.priority === 'medium',
              'border-l-blue-400': task.priority === 'low'
            }"
          >
            <div class="flex justify-between items-start mb-2">
              <h3 class="text-md font-semibold text-gray-800 dark:text-slate-200">{{ task.title }}</h3>
              <button @click="deleteTask(task.id)" class="text-gray-300 hover:text-red-500 transition-colors">
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
            
            <div class="flex flex-wrap gap-2 mt-3 items-center">
              <span class="text-[9px] px-2 py-0.5 rounded-full uppercase font-black border" :class="getPriorityClass(task.priority)">{{ task.priority }}</span>
              <span v-if="task.category" class="flex items-center text-[9px] px-2 py-0.5 rounded-full uppercase font-black border bg-purple-50 text-purple-600 border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800"><Tag class="w-2.5 h-2.5 mr-1" />{{ task.category }}</span>
              
              <div class="flex items-center text-[9px] px-2 py-0.5 rounded-full uppercase font-black border bg-gray-50 text-gray-600 border-gray-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 relative group/date cursor-pointer">
                <Calendar class="w-2.5 h-2.5 mr-1 text-gray-400" />
                <input 
                  type="date" 
                  v-model="task.due_date" 
                  :style="isDark ? 'color-scheme: dark;' : ''"
                  @change="updateTaskFields(task, 'Schedule Adjusted!')"
                  class="bg-transparent border-none outline-none cursor-pointer text-[9px] uppercase font-black font-mono w-20 p-0"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div 
        class="flex flex-col bg-green-50/30 dark:bg-green-900/10 rounded-2xl p-4 border border-green-100 dark:border-green-900/30 min-h-[400px]"
        @drop="onDrop($event, 'done')" 
        @dragenter.prevent 
        @dragover.prevent
      >
        <h2 class="text-sm font-extrabold text-green-500 dark:text-green-400 uppercase tracking-widest mb-4 flex items-center justify-between">
          <span>Done</span>
          <span class="bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 py-0.5 px-2.5 rounded-full text-xs">{{ getTasksByStatus('done').length }}</span>
        </h2>
        <div class="flex-1 space-y-3">
          <div 
            v-for="task in getTasksByStatus('done')" :key="task.id" 
            draggable="true" 
            @dragstart="startDrag($event, task)"
            class="group p-4 border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50 dark:bg-slate-800/80 shadow-sm opacity-75 hover:opacity-100 transition-all cursor-grab active:cursor-grabbing border-l-4 border-l-gray-400 dark:border-l-slate-500"
          >
            <div class="flex justify-between items-start mb-2">
              <h3 class="text-md font-semibold text-gray-500 dark:text-slate-400 line-through">{{ task.title }}</h3>
              <button @click="deleteTask(task.id)" class="text-gray-300 hover:text-red-500 transition-colors">
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
            <div class="flex flex-wrap gap-2 mt-3 items-center grayscale">
              <span class="text-[9px] px-2 py-0.5 rounded-full uppercase font-black border" :class="getPriorityClass(task.priority)">{{ task.priority }}</span>
              <div class="flex items-center text-[9px] px-2 py-0.5 rounded-full uppercase font-black border bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-slate-400 dark:border-slate-600 relative">
                <Calendar class="w-2.5 h-2.5 mr-1" />
                <input 
                  type="date" 
                  v-model="task.due_date" 
                  :style="isDark ? 'color-scheme: dark;' : ''"
                  @change="updateTaskFields(task, 'Completed Task Date Changed!')"
                  class="bg-transparent border-none outline-none cursor-pointer text-[9px] uppercase font-black font-mono w-20 p-0"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, h } from 'vue';
import { Trash2, Terminal, TerminalSquare, Plus, CheckCircle2, AlertCircle, Info, Calendar, Tag, Search, Moon, Sun, HelpCircle } from 'lucide-vue-next';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const isDark = ref(false);

const toggleTheme = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

const tasks = ref([]);
const newTask = ref({ title: '', priority: 'medium', category: 'General', due_date: '' });
const searchQuery = ref('');

const filteredTasks = computed(() => {
  return tasks.value.filter(task => {
    return task.title.toLowerCase().includes(searchQuery.value.toLowerCase());
  });
});

const getTasksByStatus = (status) => {
  return filteredTasks.value.filter(task => task.status === status);
};

const getPriorityClass = (priority) => {
  if (priority === 'high') return 'bg-red-50 text-red-600 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800';
  if (priority === 'medium') return 'bg-yellow-50 text-yellow-600 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800';
  return 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800';
};

const startDrag = (event, task) => {
  event.dataTransfer.dropEffect = 'move';
  event.dataTransfer.effectAllowed = 'move';
  event.dataTransfer.setData('taskID', task.id);
};

const onDrop = async (event, newStatus) => {
  const taskID = event.dataTransfer.getData('taskID');
  const task = tasks.value.find(t => t.id == taskID);
  
  if (task && task.status !== newStatus) {
    const oldStatus = task.status;
    task.status = newStatus; 
    
    try {
      const response = await fetch(`/api/tasks/${task.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          title: task.title, status: task.status, priority: task.priority,
          category: task.category, due_date: task.due_date
        })
      });
      
      if (response.ok) {
        // NOTE: ALL EMOJIS REMOVED FROM STRINGS
        if (newStatus === 'todo') {
          toast.info("Task moved back to To Do queue.", { 
            icon: () => h(HelpCircle, { class: 'text-blue-500 w-5 h-5' }) 
          });
        } else if (newStatus === 'in_progress') {
          toast.warning("Task is now In Progress", { 
            icon: () => h(AlertCircle, { class: 'text-yellow-500 w-5 h-5' }),
            progressClassName: 'bg-yellow-500'
          });
        } else if (newStatus === 'done') {
          toast.success("Task marked as Done!", { 
            icon: () => h(CheckCircle2, { class: 'text-green-500 w-5 h-5' }),
            progressClassName: 'bg-green-500'
          });
        }
      } else {
        task.status = oldStatus; 
      }
    } catch (error) {
      task.status = oldStatus;
      toast.error("Error moving task.", { icon: () => h(AlertCircle, { class: 'text-red-500 w-5 h-5' }) });
    }
  }
};

const updateTaskFields = async (task, successMessage) => {
  try {
    const response = await fetch(`/api/tasks/${task.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({
        title: task.title, status: task.status, priority: task.priority,
        category: task.category, due_date: task.due_date
      })
    });
    if (response.ok) {
      toast.success(successMessage, { 
        icon: () => h(Calendar, { class: 'text-green-500 w-5 h-5' }),
        progressClassName: 'bg-indigo-500'
      });
    }
  } catch (error) {
    toast.error("Error updating details.");
  }
};

const fetchTasks = async () => {
  try {
    const response = await fetch('/api/tasks');
    const data = await response.json();
    tasks.value = data;
  } catch (error) {
    toast.error("Failed to load tasks!");
  }
};

const addTask = async () => {
  if (!newTask.value.title) {
    toast.warning("Task title cannot be empty!");
    return;
  }
  try {
    const response = await fetch('/api/tasks', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({
        title: newTask.value.title, priority: newTask.value.priority,
        category: newTask.value.category, due_date: newTask.value.due_date || null,
        status: 'todo'
      })
    });
    if (response.ok) {
      newTask.value.title = ''; newTask.value.priority = 'medium'; 
      newTask.value.category = 'General'; newTask.value.due_date = '';
      fetchTasks();
      // EMOJI REMOVED
      toast.success("Task Executed Successfully!");
    }
  } catch (error) {
    toast.error("Error saving task.");
  }
};

const deleteTask = async (id) => {
  try {
    const response = await fetch(`/api/tasks/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
    if (response.ok) {
      fetchTasks(); 
      toast.error("Task Terminated.", { icon: () => h(Trash2, { class: 'text-red-500 w-5 h-5' }) });
    }
  } catch (error) {
    toast.error("Error deleting task.");
  }
};

onMounted(() => {
  fetchTasks();
});
</script>