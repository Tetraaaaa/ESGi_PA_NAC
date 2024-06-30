package apinac

import (
	"encoding/json"
	"net/http"
	"strings"

	"github.com/gorilla/mux"
)

type Departement struct{
	ID          string    `json:"id"`
	Nom			string `json:"nom"`
}

func GetAllDepartement(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, nom FROM DEPARTEMENT")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var departements []Departement
	for rows.Next() {
		var departement Departement
		err := rows.Scan(&departement.ID,&departement.Nom)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		departements = append(departements, departement)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(departements)
}

func GetDepartementByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	departementID := params["id"]

	row := db.QueryRow("SELECT id, nom FROM DEPARTEMENT WHERE id = ?", departementID)

	var departement Departement

	err := row.Scan(&departement.ID, &departement.Nom)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(departement)
}

func GetDepartementByNom(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	departementID := params["nom"]

	row := db.QueryRow("SELECT id, nom FROM DEPARTEMENT WHERE nom = ?", departementID)

	var departement Departement

	err := row.Scan(&departement.ID, &departement.Nom)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(departement)
}

func GetDepartementByResearchNom(w http.ResponseWriter, r *http.Request) {
    params := mux.Vars(r)
    userNom := params["nom"]

    query := "SELECT id, nom FROM DEPARTEMENT USER WHERE nom LIKE ?"
    rows, err := db.Query(query, "%"+strings.TrimSpace(userNom)+"%")
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }
    defer rows.Close()

    var departements []Departement

    for rows.Next() {
        var departement Departement
        err := rows.Scan(&departement.ID,&departement.Nom)
        if err != nil {
            http.Error(w, err.Error(), http.StatusInternalServerError)
            return
        }
        departements = append(departements, departement)
    }

    if err = rows.Err(); err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(departements)
}