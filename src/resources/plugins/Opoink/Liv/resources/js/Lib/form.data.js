import { useForm } from '@inertiajs/vue3';
import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';

export class FormData {
	form = null;
	fields = {};
	setForm(){
		this.form = useForm(this.fields);
	}
	setErrors(errors){
		if(Array.isArray(errors)){
			errors.forEach(error => {
				toast.add(error, 'danger');
			});
		}
		else {
			Object.keys(errors).forEach((key) => {
				let fieldErrors = errors[key];
				fieldErrors.forEach(error => {
					this.form.setError(key, error);
				});
			});
		}
	}
}