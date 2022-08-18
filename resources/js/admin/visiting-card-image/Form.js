import AppForm from '../app-components/Form/AppForm';

Vue.component('visiting-card-image-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                visiting_card_id:  '' ,
                image_link:  '' ,
                
            }
        }
    }

});