<template>
  <div class="max-w-4xl mx-auto mt-12 p-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-100">
    
    <header class="mb-10 flex flex-col items-center">
      <div class="bg-indigo-600 p-3 rounded-xl mb-4 shadow-lg shadow-indigo-200">
        <Terminal class="text-white w-8 h-8" />
      </div>
      <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600 mb-2">
        TASK MANAGER
      </h1>
    </header>

    <div class="flex gap-2 mb-10 bg-gray-50 p-2 rounded-xl border border-gray-200 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100 transition-all shadow-inner">
      
      <div class="flex-1 flex items-center pl-3">
        <TerminalSquare class="text-gray-400 w-5 h-5 mr-3" />
        <input
          v-model="newTask.title"
          @keyup.enter="addTask"
          placeholder="Enter new task..."
          class="w-full bg-transparent py-3 text-gray-700 focus:outline-none text-md"
        />
      </div>

      <div class="border-l border-gray-200 pl-2 flex items-center">
        <select 
          v-model="newTask.priority"
          class="bg-transparent text-sm font-bold text-gray-600 outline-none cursor-pointer p-2"
        >
          <option value="low">Low Pri</option>
          <option value="medium">Med Pri</option>
          <option value="high">High Pri</option>
        </select>
      </div>

      <button
        @click="addTask"
        class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-all duration-200 active:scale-95 flex items-center gap-2"
      >
        <Plus class="w-5 h-5" />
        <span>EXECUTE</span>
      </button>
    </div>

    <div v-if="tasks.length === 0" class="text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
      <Code class="mx-auto h-12 w-12 text-gray-300 mb-4" />
      <h3 class="text-lg font-medium text-gray-900">No active tasks found</h3>
    </div>

    <ul v-else class="space-y-4">
      <li
        v-for="task in tasks"
        :key="task.id"
        class="group p-4 border border-gray-100 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between bg-white shadow-sm hover:shadow-md transition-all duration-300 border-l-4"
        :class="{
          'opacity-60 grayscale': task.status === 'done',
          'border-l-red-500': task.priority === 'high' && task.status !== 'done',
          'border-l-yellow-400': task.priority === 'medium' && task.status !== 'done',
          'border-l-blue-400': task.priority === 'low' && task.status !== 'done',
          'border-l-gray-300': task.status === 'done'
        }"
      >
        <div class="flex-1 flex items-center gap-4 mb-3 sm:mb-0">
          
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-1">
              <h3 
                class="text-lg font-semibold transition-all" 
                :class="task.status === 'done' ? 'line-through text-gray-400' : 'text-gray-800'"
              >
                {{ task.title }}
              </h3>
              
              <span 
                class="text-[10px] px-2 py-0.5 rounded-full uppercase font-black tracking-widest border"
                :class="{
                  'bg-red-50 text-red-600 border-red-200': task.priority === 'high',
                  'bg-yellow-50 text-yellow-600 border-yellow-200': task.priority === 'medium',
                  'bg-blue-50 text-blue-600 border-blue-200': task.priority === 'low',
                }"
              >
                {{ task.priority }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3 ml-12 sm:ml-0">
          <select 
            v-model="task.status" 
            @change="updateStatus(task)"
            class="text-xs font-bold uppercase tracking-wider rounded-md px-3 py-2 outline-none cursor-pointer transition-colors border"
            :class="{
              'bg-gray-50 text-gray-600 border-gray-200': task.status === 'todo',
              'bg-indigo-50 text-indigo-700 border-indigo-200': task.status === 'in_progress',
              'bg-green-50 text-green-700 border-green-200': task.status === 'done'
            }"
          >
            <option value="todo">To Do</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
          </select>

          <button
            @click="deleteTask(task.id)"
            class="p-2 text-gray-400 hover:text-white hover:bg-red-500 rounded-lg transition-all duration-200"
          >
            <Trash2 class="w-5 h-5" />
          </button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
// 'h' kiyana function eka import karanawa custom icons render karanna
import { ref, onMounted, h } from 'vue';

// Lucide Icons Import (Aluthin CheckCircle, AlertCircle, Info add kala)
import { Trash2, Terminal, TerminalSquare, Code, Plus, CheckCircle2, AlertCircle, Info } from 'lucide-vue-next';

// Vue3 Toastify Import
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const tasks = ref([]);
const newTask = ref({ title: '', priority: 'medium' });

const fetchTasks = async () => {
  try {
    const response = await fetch('/api/tasks');
    const data = await response.json();
    tasks.value = data;
  } catch (error) {
    toast.error("Failed to load tasks!", {
      icon: () => h(AlertCircle, { class: 'text-red-500 w-5 h-5' })
    });
  }
};

const addTask = async () => {
  if (!newTask.value.title) {
    toast.warning("Task title cannot be empty!", {
      icon: () => h(AlertCircle, { class: 'text-yellow-500 w-5 h-5' })
    });
    return;
  }

  try {
    const response = await fetch('/api/tasks', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        title: newTask.value.title,
        priority: newTask.value.priority,
        status: 'todo'
      })
    });

    if (response.ok) {
      newTask.value.title = '';
      newTask.value.priority = 'medium'; 
      fetchTasks();
      
      // Custom icon ekka success toast eka
      toast.success("Task Executed Successfully!", {
        icon: () => h(CheckCircle2, { class: 'text-green-500 w-5 h-5' }),
        progressClassName: 'bg-green-500' // Progress bar eke color eka wenas karanna
      });
    }
  } catch (error) {
    toast.error("Error saving task.", {
      icon: () => h(AlertCircle, { class: 'text-red-500 w-5 h-5' })
    });
  }
};

const updateStatus = async (task) => {
  try {
    const response = await fetch(`/api/tasks/${task.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        title: task.title,
        status: task.status,
        priority: task.priority
      })
    });

    if (response.ok) {
      if(task.status === 'done') {
        toast.success("Task marked as Done!", {
          icon: () => h(CheckCircle2, { class: 'text-green-500 w-5 h-5' })
        });
      } else {
        toast.info("Task status updated.", {
          icon: () => h(Info, { class: 'text-blue-500 w-5 h-5' })
        });
      }
    }
  } catch (error) {
    toast.error("Error updating status.", {
      icon: () => h(AlertCircle, { class: 'text-red-500 w-5 h-5' })
    });
  }
};

const deleteTask = async (id) => {
  try {
    const response = await fetch(`/api/tasks/${id}`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json' }
    });

    if (response.ok) {
      fetchTasks();
      // Custom trash icon ekka delete toast eka
      toast.error("Task Terminated.", {
        icon: () => h(Trash2, { class: 'text-red-500 w-5 h-5' })
      });
    }
  } catch (error) {
    toast.error("Error deleting task.", {
      icon: () => h(AlertCircle, { class: 'text-red-500 w-5 h-5' })
    });
  }
};

onMounted(() => {
  fetchTasks();
});
</script>