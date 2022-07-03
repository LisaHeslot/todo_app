import './styles/app.css';
import './bootstrap';

import Vue from 'vue'
import App from './js/App.vue'

new Vue({
    render(h) {
        return h(App, {
            props: {
                userData: JSON.parse(this.$el.getAttribute('user-data')),
            },
        })
    },
}).$mount('#app')