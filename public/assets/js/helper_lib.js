/* 
    require : 'YYYY-MM-DD'
    return : 'DD-MM-YYYY'
*/
function datefsql(date) {
    if (date !== undefined && date !== null && date !== 'null') {
        var elem = date.split('-');
        var tahun = elem[0];
        var bulan = elem[1];
        var hari = elem[2];
        return hari + '/' + bulan + '/' + tahun;
    } else {
        return '';
    }
}
