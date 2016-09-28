/**
 * @param {SVGElement} svg
 * @param {Function} callback
 * @param {jsPDF} callback.pdf
 * */
$(document).ready(function(){

  $("#download").click(function(){
    var i = 0;
    var frm = $("<form>",{
      'action': document.location.origin+'/wpconstruction/constructions/download_charts',
      'method': 'post',
    });
    frm.append($("<input>",{
      'name': 'job_id',
      'value': job_id
    }));

    while(i < $("svg").length){

      svgAsDataUri($("svg").get(i), {}, function(svg_uri) {

        frm.append($("<textarea>",{
          'name': 'svg[]',
          'text': svg_uri
        }));

        if(i+1 == $("svg").length){
          //download_pdf('all_jobs_overview.pdf', doc.output('dataurlstring'));
		    frm.append($("<input>",{
		      'name': 'w',
		      'value': $("#chartContainer").width()
		    }));
		    frm.append($("<input>",{
		      'name': 'h',
		      'value': $("#chartContainer").height()
		    }));
          frm.appendTo('body').submit().remove();
        }

      });


      i++;

    }

    //download_pdf('all_jobs_overview.pdf', doc.output('dataurlstring'));
  })
});
