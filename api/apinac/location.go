package apinac

import (
	"encoding/json"
	"net/http"

	"github.com/gorilla/mux"
)

type Location struct {
	ID          int    `json:"id"`
	UserID      int    `json:"userId"`
	LogementID  int    `json:"logementId"`
	DateDebut   string `json:"dateDebut"`
	DateFin     string `json:"dateFin"`
}

func GetAllLocations(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, id_USER, id_LOGEMENT, date_debut, date_fin FROM LOCATION")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var locations []Location
	for rows.Next() {
		var location Location
		err := rows.Scan(&location.ID, &location.UserID, &location.LogementID,  &location.DateDebut, &location.DateFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
			locations = append(locations, location)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(locations)
}

func GetLocationByID(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	locationID := params["id"]

	row := db.QueryRow("SELECT id, id_USER, id_LOGEMENT, nom, taille, emplacement, date_debut, date_fin FROM LOCATION WHERE id = ?", locationID)

	var location Location

	err := row.Scan(&location.ID, &location.UserID, &location.LogementID, &location.DateDebut, &location.DateFin)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(location)
}

func GetLocationByIDLogement(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	locationIDLogement := params["id"]

	rows, err := db.Query("SELECT id, id_USER, id_LOGEMENT, date_debut, date_fin FROM LOCATION WHERE id_LOGEMENT=?",locationIDLogement)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var locations []Location
	for rows.Next() {
		var location Location
		err := rows.Scan(&location.ID, &location.UserID, &location.LogementID,  &location.DateDebut, &location.DateFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
			locations = append(locations, location)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(locations)
}

func GetLocationByIDUser(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	locationIDUser := params["id"]

	rows, err := db.Query("SELECT id, id_USER, id_LOGEMENT, date_debut, date_fin FROM LOCATION WHERE id_USER=?",locationIDUser)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var locations []Location
	for rows.Next() {
		var location Location
		err := rows.Scan(&location.ID, &location.UserID, &location.LogementID,  &location.DateDebut, &location.DateFin)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
			locations = append(locations, location)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(locations)
}