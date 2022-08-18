import AppForm from '../app-components/Form/AppForm';

Vue.component('curl-switch-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                status:  '' ,
                
            }
        }
    }

});