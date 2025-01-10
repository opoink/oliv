import { reactive } from 'vue';

export const loader = reactive({
	isActive: false,
	content: 'Loading...',
	setLoader(isActive, content=''){
		this.isActive = isActive;
		this.setContent(content);
	},
	setContent(content){
		this.content = content;
	}
});