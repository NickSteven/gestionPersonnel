const employes= document.getElementById('employes');

if(employes){
employes.addEventListener("click", e => {
	if (e.target.className === 'btn btn-danger delete-employe') {
		if (confirm('Etes-vous sûr?')) {
			const id = e.target.getAttribute('data-id');

			fetch(`/gest_personnel/delete/${id}`, {
				method: 'DELETE'
			}).then(res => window.location.reload());
		}
	}
});
}