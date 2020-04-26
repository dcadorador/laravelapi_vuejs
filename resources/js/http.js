import axios from "axios";
import Vue from 'vue'

const envURL = process.env.MIX_APP_URL
const devInstance = createInstance(envURL ? envURL : "http://machshipsync.local/");
const productionInstance = createInstance("http://localhost:3000"); // will change later

function createInstance(baseURL){
    return axios.create({
        baseURL,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`
        }
    });
}

export default {
    install () {
        Vue.prototype.$http = devInstance
    }
};