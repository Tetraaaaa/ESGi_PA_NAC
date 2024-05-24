package apinac

import (
	"encoding/json"
	"net/http"

	"github.com/gorilla/mux"
)

type Status struct {
	ID   int    `json:"id"`
	Name string `json:"name"`
}



func GetAllStatus(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, name FROM STATUS")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var statuses []Status
	for rows.Next() {
		var status Status
		err := rows.Scan(&status.ID, &status.Name)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		statuses = append(statuses, status)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(statuses)
}

func GetStatusByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	statusID := params["id"]

	row := db.QueryRow("SELECT id, name FROM STATUS WHERE id = ?", statusID)

	var status Status
	err := row.Scan(&status.ID, &status.Name)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(status)
}