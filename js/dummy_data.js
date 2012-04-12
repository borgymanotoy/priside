var locations = new Array();
var location_states = new Array();

// START: Location object
function Location(){
        this.city = "";
        this.state = "";
        this.country = "";
        this.zip = "";
}
function Location(city, state, country, zip){
        this.city = city;
        this.state = state;
        this.country = country;
        this.zip = zip;
}

// START: Location object
function Location_state(){
        this.name = "";
        this.abbr = "";
}
function Location_state(name, abbr){
        this.name = name;
        this.abbr = abbr;
}

location_states.push(new Location("Davao del sur", "DVO_SUR"));
location_states.push(new Location("Davao del norte", "DVO_NOR"));
location_states.push(new Location("Davao oriental", "DVO_OR"));
location_states.push(new Location("Surigao del sur", "SRG_SUR"));

locations.push(new Location("Davao City", "DVO_SUR", "Philippines", "8000"));
locations.push(new Location("Digos City", "DVO_SUR", "Philippines", "8001"));
locations.push(new Location("Bansalan", "DVO_SUR", "Philippines", "8002"));
locations.push(new Location("Don Marcelino", "DVO_SUR", "Philippines", "8003"));
locations.push(new Location("Hagonoy", "DVO_SUR", "Philippines", "8004"));
locations.push(new Location("Jose Abad Santos (Trinidad)", "DVO_SUR", "Philippines", "8005"));
locations.push(new Location("Kiblawan", "DVO_SUR", "Philippines", "8006"));
locations.push(new Location("Magsaysay", "DVO_SUR", "Philippines", "8007"));
locations.push(new Location("Malalag", "DVO_SUR", "Philippines", "8008"));
locations.push(new Location("Malita", "DVO_SUR", "Philippines", "8009"));
locations.push(new Location("Matanao", "DVO_SUR", "Philippines", "8010"));
locations.push(new Location("Padada", "DVO_SUR", "Philippines", "8011"));
locations.push(new Location("Santa Cruz", "DVO_SUR", "Philippines", "8012"));
locations.push(new Location("Santa Maria", "DVO_SUR", "Philippines", "8013"));
locations.push(new Location("Sarangani", "DVO_SUR", "Philippines", "8014"));
locations.push(new Location("Sulop", "DVO_SUR", "Philippines", "8015"));

locations.push(new Location("Asuncion", "DVO_NOR", "Philippines", "8024"));
locations.push(new Location("Braulio E. Dujali", "DVO_NOR", "Philippines", "8025"));
locations.push(new Location("Carmen", "DVO_NOR", "Philippines", "8026"));
locations.push(new Location("Kapalong", "DVO_NOR", "Philippines", "8027"));
locations.push(new Location("New Corella", "DVO_NOR", "Philippines", "8016"));
locations.push(new Location("Panabo City", "DVO_NOR", "Philippines", "8017"));
locations.push(new Location("Island Garden City of Samal", "DVO_NOR", "Philippines", "8018"));
locations.push(new Location("San Isidro", "DVO_NOR", "Philippines", "8019"));
locations.push(new Location("Santo Tomas", "DVO_NOR", "Philippines", "8020"));
locations.push(new Location("Tagum City", "DVO_NOR", "Philippines", "8021"));
locations.push(new Location("Talaingod", "DVO_NOR", "Philippines", "8022"));

locations.push(new Location("Baganga", "DVO_OR", "Philippines", "8145"));
locations.push(new Location("Banaybanay", "DVO_OR", "Philippines", "8146"));
locations.push(new Location("Boston", "DVO_OR", "Philippines", "8147"));
locations.push(new Location("Caraga", "DVO_OR", "Philippines", "8148"));
locations.push(new Location("Cateel", "DVO_OR", "Philippines", "8149"));
locations.push(new Location("Governor Generoso", "DVO_OR", "Philippines", "8150"));
locations.push(new Location("Lupon", "DVO_OR", "Philippines", "8151"));
locations.push(new Location("Manay", "DVO_OR", "Philippines", "8152"));
locations.push(new Location("San Isidro", "DVO_OR", "Philippines", "8153"));
locations.push(new Location("Tarragona", "DVO_OR", "Philippines", "8154"));

locations.push(new Location("Barobo", "SRG_SUR", "Philippines", "8311"));
locations.push(new Location("Bayabas", "SRG_SUR", "Philippines", "8312"));
locations.push(new Location("Cagwait", "SRG_SUR", "Philippines", "8313"));
locations.push(new Location("Cantilan", "SRG_SUR", "Philippines", "8314"));
locations.push(new Location("Carmen", "SRG_SUR", "Philippines", "8315"));
locations.push(new Location("Carrascal", "SRG_SUR", "Philippines", "8316"));
locations.push(new Location("Cortes", "SRG_SUR", "Philippines", "8317"));
locations.push(new Location("Hinatuan", "SRG_SUR", "Philippines", "8318"));
locations.push(new Location("Lanuza", "SRG_SUR", "Philippines", "8319"));
locations.push(new Location("Lianga", "SRG_SUR", "Philippines", "8320"));
locations.push(new Location("Lingig", "SRG_SUR", "Philippines", "8321"));
locations.push(new Location("Madrid", "SRG_SUR", "Philippines", "8322"));
locations.push(new Location("Marihatag", "SRG_SUR", "Philippines", "8323"));
locations.push(new Location("San Agustin", "SRG_SUR", "Philippines", "8324"));
locations.push(new Location("San Miguel", "SRG_SUR", "Philippines", "8325"));
locations.push(new Location("Tagbina", "SRG_SUR", "Philippines", "8326"));
locations.push(new Location("Tago", "SRG_SUR", "Philippines", "8327"));
