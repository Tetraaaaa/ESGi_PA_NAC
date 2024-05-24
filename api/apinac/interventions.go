package apinac

import (
	"encoding/json"
	"net/http"
)

type Intervention struct {
	ID           int `json:"id"`
	ServiceID    int `json:"serviceId"`
	DepartementID int `json:"departementId"`
}

// Endpoint pour voir tous les logements

func GetAllInterventions(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_service, id_departement FROM INTERVENTION")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var interventions []Intervention
	for rows.Next() {
		var intervention Intervention
		err := rows.Scan(&intervention.ID, &intervention.ServiceID, &intervention.DepartementID)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		interventions = append(interventions, intervention)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(interventions)
}