package main

import (
	"api/apinac"
	"api/bdd"
	"api/pdfnac"
	"database/sql"
	"log"
	"net/http"

	_ "github.com/go-sql-driver/mysql"
	"github.com/gorilla/mux"
)

var db *sql.DB

func main() {
	var err error
	db, err = bdd.ConnectDB()
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()
	apinac.SetDB(db)
	pdfnac.SetDB(db)

	r := mux.NewRouter()

	//Users
	r.HandleFunc("/users", apinac.GetAllUsers).Methods("GET")
	r.HandleFunc("/users/id/{id}", apinac.GetUserByID).Methods("GET")

	r.HandleFunc("/users/email",apinac.GetAllEmailUser).Methods("GET")
	r.HandleFunc("/users/email/{email}",apinac.GetUserByEmail).Methods("GET")
	r.HandleFunc("/users/research-email/{email}",apinac.GetUserByResearchEmail).Methods("GET")

	r.HandleFunc("/users/prenom/{prenom}",apinac.GetUserByPrenom).Methods("GET")
	r.HandleFunc("/users/nom/{nom}",apinac.GetUserByNom).Methods("GET")
	r.HandleFunc("/users/research-nom/{nom}",apinac.GetUserByResearchNom).Methods("GET")
	r.HandleFunc("/users/status/{status}",apinac.GetUserByStatus).Methods("GET")


	r.HandleFunc("/users", apinac.AddUser).Methods("POST")

	//Services
	r.HandleFunc("/services", apinac.GetAllServices).Methods("GET")
	r.HandleFunc("/services/id/{id}", apinac.GetServiceByID).Methods("GET")
	r.HandleFunc("/services/type/{type}", apinac.GetServiceByType).Methods("GET")
	r.HandleFunc("/services/user/{user}", apinac.GetServiceByIDUser).Methods("GET")
	r.HandleFunc("/services", apinac.AddService).Methods("POST")

	r.HandleFunc("/services/demande", apinac.GetAllServiceDemande).Methods("GET")
	r.HandleFunc("/services/demande/logement/{id}", apinac.GetServiceDemandeByLogemnt).Methods("GET")
	r.HandleFunc("/services/demande/service/{id}", apinac.GetServiceDemandeByService).Methods("GET")
	r.HandleFunc("/services/demande/status/{status}", apinac.GetServiceDemandeByStatus).Methods("GET")

	r.HandleFunc("/services/calendrier", apinac.GetAllCalendrier).Methods("GET")
	r.HandleFunc("/services/calendrier/date/{date}", apinac.GetCalendrierByDate).Methods("GET")
	r.HandleFunc("/services/calendrier/service/{service}", apinac.GetCalendrierByServices).Methods("GET")

	//Locations
	r.HandleFunc("/locations", apinac.GetAllLocations).Methods("GET")
	r.HandleFunc("/locations/id/{id}", apinac.GetLocationByID).Methods("GET")
	r.HandleFunc("/locations/logement/id/{id}", apinac.GetLocationByIDLogement).Methods("GET")
	r.HandleFunc("/locations/user/id/{id}", apinac.GetLocationByIDUser).Methods("GET")
	r.HandleFunc("/locations/date/{date}", apinac.GetLocationByDate).Methods("GET")
	r.HandleFunc("/locations/date/{start}/{end}", apinac.GetLocationByDateRange).Methods("GET")
	r.HandleFunc("/locations/date/{start}/{end}/logement/{logement}", apinac.GetLocationByLogementAndDateRange).Methods("GET")
	r.HandleFunc("/locations/date/{start}/{end}/user/{user}", apinac.GetLocationByUserAndDateRange).Methods("GET")



	//Logements
	r.HandleFunc("/logements", apinac.GetAllLogements).Methods("GET")
	r.HandleFunc("/logements/id/{id}", apinac.GetLogementByID).Methods("GET")
	r.HandleFunc("/logements/pays/{pays}", apinac.GetLogementByPays).Methods("GET")
	r.HandleFunc("/logements/code-postal/{code_postal}", apinac.GetLogementByCodePostal).Methods("GET")
	r.HandleFunc("/logements/ville/{ville}", apinac.GetLogementByVille).Methods("GET")
	r.HandleFunc("/logements/capacite/{min_capacite}/{max_capacite}", apinac.GetLogementByCapacite).Methods("GET")
	r.HandleFunc("/logements/type", apinac.GetAllTypeLogement).Methods("GET")
	r.HandleFunc("/logements/type/{type}", apinac.GetLogementByType).Methods("GET")
	r.HandleFunc("/logements/type/id/{id}", apinac.GetTypeLogementByID).Methods("GET")
	r.HandleFunc("/logements", apinac.AddLogement).Methods("POST")

	//Status
	r.HandleFunc("/status", apinac.GetAllStatus).Methods("GET")
	r.HandleFunc("/status/id/{id}", apinac.GetStatusByID).Methods("GET")

	//Grade
	r.HandleFunc("/grade", apinac.GetAllGrade).Methods("GET")
	r.HandleFunc("/grade/user/{user}", apinac.GetGradebyUser).Methods("GET")
	r.HandleFunc("/grade/grade/{grade}", apinac.GetGradebyGrade).Methods("GET")

	r.HandleFunc("/grade/type", apinac.GetAllTypeGrade).Methods("GET")
	r.HandleFunc("/grade/type/id/{id}", apinac.GetTypeGradeByID).Methods("GET")

	r.HandleFunc("/grade/valide", apinac.GetValidGrades).Methods("GET")
	r.HandleFunc("/grade/valide/true", apinac.GetValidGrades).Methods("GET")
	r.HandleFunc("/grade/valide/false", apinac.GetInvalidGrades).Methods("GET")


	//Departement
	r.HandleFunc("/departements", apinac.GetAllDepartement).Methods("GET")
	r.HandleFunc("/departements/id/{id}", apinac.GetDepartementByID).Methods("GET")
	r.HandleFunc("/departements/nom/{nom}", apinac.GetDepartementByNom).Methods("GET")
	r.HandleFunc("/departements/research-nom/{nom}", apinac.GetDepartementByResearchNom).Methods("GET")

	//Interventions
	r.HandleFunc("/interventions", apinac.GetAllInterventions).Methods("GET")

	//PDF
	r.HandleFunc("/pdflocations/{id}", pdfnac.GetPdfLocationByID).Methods("GET")


	log.Fatal(http.ListenAndServe(":8080", r))
}

