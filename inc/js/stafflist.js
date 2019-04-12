const makeStaffObject = (person) =>{
    const personobj = {};
    const areas = [];
    const shows = [];

    let fullname = person.title ? ( person.title.rendered ? person.title.rendered : '' ) : '';

    if (person.acf && person.acf.staff_area) {
        person.acf.staff_area.forEach((area) => {
            areas.push(area.name);
        });
    }
    if (person.acf && person.acf.linked_show) {
        person.acf.linked_show.forEach((show) => {
            shows.push(show.ID);
        });
    }

    return {
        'name': fullname,
        'lastname': fullname.split(" ").pop(),
        'role': person.acf ? ( person.acf.role ? person.acf.role : '' ) : '',
        shows,
        areas,
        'photo': person._embedded['wp:featuredmedia'][0].source_url ? person._embedded['wp:featuredmedia'][0].source_url:
            'https://www.udpac.org/wp-content/uploads/2011/08/UDSS_Logo-BlackUD-300x252.png',
        'bio': person.content.rendered
    };
};

const printStaff = (el, staffMembers, area_param, show_param) => {
    let newRow = '';
    let printedTitle = false;
    let printStaffer = true;

    staffMembers.forEach((staff)=>{

        if(show_param){
            if( staff.shows.indexOf(show_param) >= 0 ){
                printStaffer = true;
            } else {
                printStaffer = false;
            }
        }
        if(area_param){
            if(staff.areas.indexOf(area_param) >= 0){
                printStaffer = true;
            } else {
                printStaffer = false;
            }
        }

        if(printStaffer) {
            const nameStr = staff.name.replace(/[\W_]/gi, '').toLowerCase();
            const area = area_param ? area_param : 'Production Staff';
            const areaStr = area.replace(/[\W_]/gi, '').toLowerCase();
            const staffID = nameStr + areaStr;

            if(!printedTitle){
                newRow += '<h2>' + area + '</h2><div class="row staff-row">';
                printedTitle = true;
            }

            let template = `<div class="col-sm-4 staff-member">
                <img src="' + staff.photo + '" class="img-responsive">
                <h3 class="staff-name">${staff.name}</h3>
                <h4 class="staff-role"><em>${staff.role}</em></h4>`;

            if(staff.bio.length > 1){
                template += `
                <button class="btn btn-udpacyellow" type="button"
                data-toggle="collapse" data-target="#${staffID}"
                aria-expanded="false" aria-controls="${staffID}">Read More <i class="fa fa-plus"></i></button>
                <div class="collapse" id="'+staffID+'"><div class="card card-body">${staff.bio}</div></div>`;
            }
            template += `</div>`;
            newRow += template;
        }

    });

    newRow += '</div>';
    el.append(newRow);

};
