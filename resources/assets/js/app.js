
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('familia', require('./components/Familia.vue'));
Vue.component('producto', require('./components/Producto.vue'));
Vue.component('cliente', require('./components/Cliente.vue'));
Vue.component('proveedor', require('./components/Proveedor.vue'));
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('ingreso', require('./components/Ingreso.vue'));
Vue.component('venta', require('./components/Venta.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('graduacion', require('./components/Graduacion.vue'));
Vue.component('consultaingreso', require('./components/ConsultaIngreso.vue'));
Vue.component('consultaventa', require('./components/ConsultaVenta.vue'));
Vue.component('consultacaja', require('./components/ConsultaCaja.vue'));
Vue.component('kardex', require('./components/Kardex.vue'));

Vue.component('notificacion', require('./components/Notification.vue'));
Vue.component('caja', require('./components/Caja.vue'));
Vue.component('egresos', require('./components/Egresos.vue'));
Vue.component('cobro', require('./components/Cobro.vue'));
Vue.component('pago', require('./components/Pago.vue'));
Vue.component('empresa', require('./components/Empresa.vue'));
Vue.component('sucursal', require('./components/Sucursal.vue'));

const app = new Vue({
    el: '#app',
    data: {
        menu : 0,
        notifications: []
    },
    created() {
        let me = this;
        axios.post('notification/get').then(function(response) {
            //console.log(response.data);
            me.notifications = response.data;
        }).catch(function(error) {
            console.log(error);
        });

        var userId = $('meta[name="userId"]').attr('content');
        Echo.private('App.User.' + userId).notification((notification) => {
            me.notifications.unshift(notification);
        });
    }
});
