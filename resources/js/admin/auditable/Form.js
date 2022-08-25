import AppForm from '../app-components/Form/AppForm';

Vue.component('auditable-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                email:  '' ,
                phone:  '' ,
                city:  '' ,
                status:  '' ,
                
            }
        }
    }

});