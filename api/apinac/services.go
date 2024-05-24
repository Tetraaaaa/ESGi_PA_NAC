package apinac

import (
	"encoding/json"
	"net/http"

	"github.com/gorilla/mux"
)

type Service struct {
	ID          int    `json:"id"`
	UserID      int    `json:"userId"`
	Description string `json:"description"`
	Type        string `json:"type"`
}

func AddService(w http.ResponseWriter, r *http.Request) {
    var service Service
    // Décodez le corps de la demande JSON dans la structure Service
    err := json.NewDecoder(r.Body).Decode(&service)
    if err != nil {
        http.Error(w, err.Error(), http.StatusBadRequest)
        return
    }

    // Insérez le service dans la base de données
    _, err = db.Exec("INSERT INTO SERVICE (id_USER, description, type) VALUES (?, ?, ?)",
        service.UserID, service.Description, service.Type)
    if err != nil {
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    // Renvoyer une réponse 201 Created
    w.WriteHeader(http.StatusCreated)
}

func GetAllServices(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_USER, description, type FROM SERVICE")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var services []Service
	for rows.Next() {
		var service Service
		err := rows.Scan(&service.ID, &service.UserID, &service.Description, &service.Type)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		services = append(services, service)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(services)
}

func GetServiceByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	serviceID := params["id"]

	row := db.QueryRow("SELECT id, id_USER, description, type FROM SERVICE WHERE id = ?", serviceID)

	var service Service

	err := row.Scan(&service.ID, &service.UserID, &service.Description, &service.Type)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(service)
}