<template>
  <div>
    <div class="pt-16">
      <h1 class="text-3xl font-semibold mb-4">Enter your phone number</h1>
      <form action="#" @submit.prevent="handlLogin" v-if="!verify">
        <div
          class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left"
        >
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
              <input
                type="text"
                v-model="data.phone"
                v-maska
                data-maska="+############"
                placeholder="+(43)24546"
                id="phone"
                name="phone"
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm"
              />
              <p class="py-2 text-red-500" v-if="errors.phone">
                {{ errors.phone }}
              </p>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button
              type="submit"
              @submit.prevent="handlLogin"
              class="text-white inline-flex justify-center rounded-md border border-transparent bg-black py-2"
            >
              Continue
            </button>
          </div>
        </div>
      </form>

      <!-- verify form -->
      <form action="#" @submit.prevent="handlVerify" v-else>
        <div
          class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left"
        >
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
              <input
                type="text"
                v-model="data.phone"
                disabled
                v-maska
                data-maska="+############"
                placeholder="+(43)24546"
                id="phone"
                name="phone"
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm"
              />
              <!-- <p class="py-2 text-red-500" v-if="errors.phone">
                {{ errors.phone }}
              </p> -->
            </div>
          </div>
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
              <input
                type="text"
                v-model="data.login_code"
                v-maska
                data-maska="######"
                placeholder="Enter your code"
                id="login-code"
                name="login_code"
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm"
              />
              <p class="py-2 text-red-500" v-if="errors.login_code">
                {{ errors.login_code }}
              </p>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button
              type="submit"
              @submit.prevent="handlVerify"
              class="text-white inline-flex justify-center rounded-md border border-transparent bg-black py-2"
            >
              Continue
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { vMaska } from 'maska'
import { onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
const router = useRouter()
let verify = ref(false)
let data = reactive({
  phone: null,
  login_code: null,
})
let errors = reactive({
  phone: null,
  login_code: null,
})

onMounted(() => {
  if (localStorage.getItem('token')) {
    router.push({
      name: 'landing',
    })
  }
})
const handlLogin = () => {
  axios
    .post('http://127.0.0.1:8000/api/login', data)
    .then((response) => {
      verify.value = true
    })
    .catch((error) =>  (errors.phone = error.response.data.errors.phone[0]))
}
const handlVerify = () => {
  axios
    .post('http://127.0.0.1:8000/api/verify', data)
    .then((response) => {
      localStorage.setItem('token', response.data)
      router.push({
        name: 'landing',
      })
    })
    .catch((error) => (errors.login_code = error.response.data.errors.login_code[0]))
}
</script>
<style></style>
