
async function del_hotel_pa(id,id_pa) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
        const res = await fetch("del_hotel_pa.php?id="+id);
        const str = await res.text();
        window.location.href="hotel_pa.php?id_pa="+id_pa+"&message="+str;
    }
}

async function del_user(id) {

    if (confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
        const res = await fetch("del_user.php?id="+id);
        const str = await res.text();
        window.location.href="user.php?message="+str;
    }
}

async function search(){
    const text = document.getElementById("search").value;
    const res = await fetch("search_user.php?search="+text);
    const str =await res.text();

    tbody=document.getElementById("recherche");
    tbody.innerHTML = str;
}