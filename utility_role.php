<?php
namespace Role {

function isStudentAdmin()        { return ( $_SESSION[ 'StudentAdmin'  ] == 1 ); }
function isGroupAdmin()          { return ( $_SESSION[ 'GroupAdmin'    ] == 1 ); }
function isFinanceAdmin()        { return ( $_SESSION[ 'FinanceAdmin'  ] == 1 ); }
function isStaffAdmin()          { return ( $_SESSION[ 'StaffAdmin'    ] == 1 ); }
function isTeacher()             { return ( $_SESSION[ 'Teacher'       ] == 1 ); }
}

