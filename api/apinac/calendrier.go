package apinac

import (
	"encoding/json"
	"net/http"

	"github.com/gorilla/mux"
)

type Calendrier struct {
	ID        int    `json:"id"`
	Date      string `json:"date"`
	ServiceID int    `json:"serviceId"`
}

func GetAllCalendrier(w http.ResponseWriter, r *http.Request) {
	rows, err := db.Query("SELECT id, date, id_SERVICE FROM CALENDRIER")
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var calendrier []Calendrier
	for rows.Next() {
		var c Calendrier
		err := rows.Scan(&c.ID, &c.Date, &c.ServiceID)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		calendrier = append(calendrier, c)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(calendrier)
}

func GetCalendrierByDate(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	calendrierDate := params["date"]

	rows, err := db.Query("SELECT id, date, id_SERVICE FROM CALENDRIER WHERE date=?",calendrierDate)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var calendriers []Calendrier
	for rows.Next() {
		var calendrier Calendrier
		err := rows.Scan(&calendrier.ID, &calendrier.Date, &calendrier.ServiceID)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		calendriers = append(calendriers, calendrier)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(calendriers)
}

func GetCalendrierByServices(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	calendrierServices := params["service"]

	rows, err := db.Query("SELECT id, date, id_SERVICE FROM CALENDRIER WHERE id_SERVICE=?",calendrierServices)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	var calendriers []Calendrier
	for rows.Next() {
		var calendrier Calendrier
		err := rows.Scan(&calendrier.ID, &calendrier.Date, &calendrier.ServiceID)
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		calendriers = append(calendriers, calendrier)
	}

	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(calendriers)
}