import AppForm from '../app-components/Form/AppForm';

Vue.component('user-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                bussiness_id:  '' ,
                name:  '' ,
                phone:  '' ,
                latlong:  '' ,
                status:  '' ,
                
            }
        }
    }

});