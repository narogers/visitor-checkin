@extends('layouts/master')

@section('content')

         <h2>Policies and Terms of Use</h2>
         <p class="lead">The Ingalls Library is a non-circulating art research collection intended to assist visitors with research needs that cannot be met by local public, university, and college library collections. Visitors are expected to complete the Patron Registration Form and this Policies and Terms of Use Agreement, and to abide by the stated policies with respect for the rights of others and with respect for the collections. Failure to do so will result in loss of library privileges and removal from the library and the Museum. Access to the Ingalls Library and Museum Archives is granted at the sole discretion of the library’s staff and such access may be denied or revoked at any time.</p>
         <h3>Library Use Policies</h3>
                    <p>All briefcases, book bags, backpacks, etc. must be checked at the museum’s north entrance lobby coat check. Only coats, loose personal papers, books, laptop computers, and purses may be brought into the library.</p>
                    <p>Library and archival material may not be removed from the premises. Personal materials are subject to search upon leaving. Anyone who removes materials from the Ingalls Library without authorization will lose all privileges and may be assessed a fine and any replacement costs of the material.</p>
                    <p>Please turn off or silence your cell phone while using the library. Cell phones may be used in the library lounge on level 2.</p>
                    <p>Food may not be brought into or consumed in the library. Lidded beverages may be consumed in the reading room, seminar room, and library lounge on level 2. Beverages are not allowed in the reference area, browsing area, or in the archives.</p>
                    <p>Library computers may be used by registered library visitors for art research only. Library terminals are provided for short-term use limited to thirty minutes.</p>
                    <p>Library visitors are expected to use personal laptop computers for long term research and personal needs while in the library.</p>
                    <p>The use of personal scanners is not allowed in the library.</p>
                    <p>Do NOT mark pages, use Post-It notes or write on library and archival materials.</p>

                    <h3>Museum Archives Use Policies</h3>
                    <p>The museum archivist determines the collections that are available. Some archival collections are restricted or permanently closed to researchers.</p>
                    <p>Researchers must request collections using call slips provided. Archives staff retrieve materials as needed and available.</p>
                    <p>Researchers may use one box of records or one folder of oversized material at a time. Please return material to reference or circulation staff before requesting another box or folder.</p>
                    <p>Researchers must use pencil for taking notes.</p>
                    <p>Researchers may be asked to wear cotton gloves when handling certain types of materials.</p>
                    <p>Materials MUST be kept in the order in which they are found within each box and folder.</p>
                    <p>Reproduction of archival material is at the discretion of the archivist.</p>
                    <p>All copies will be made by staff. Do not remove items to be copied. Instructions for flagging items to be copied will be provided. Copies are $0.25 each.</p>
                    <p>Citations should acknowledge The Cleveland Museum of Art Archives and note the collection name, box number, folder title, and date.</p>

                    <h3>Online Databases</h3>
                    <p>The United States Copyright Law (Title 17, U.S. Code) governs the making of copies of copyrighted materials. The person using the databases offered here is liable for any infringement.</p>
                    <p>The following are general restrictions on the use of the electronic databases. Others may also apply. Please request information from the Director of Library and Archives about restrictions associated with specific databases offered by the library.</p>
                    <p>Access is allowed only to employees of this institution at the employee’s desktop, or when allowed to authorized visitors at the library’s online public access terminals.</p>
                    <p>Copies of data from the databases may be made as long as they do not contain substantial or significant segments of the databases.</p>
                    <p>Information from the databases may not be used for interlibrary lending, including the provision of copies to libraries, institutions, or persons. No part of the databases may be transmitted over the Internet. Databases may be used only for research, study, and non-commercial purposes.</p>
                    <p>Information from databases may not be used to create derivative or competitive works.</p>

                    <h3>Ingalls Library Image Database Restrictions</h3>
                    <p>The United States Copyright Law (Title 17, U.S. Code) governs the making of copies of copyrighted materials. Digital images in the online system of the Image Library are copyrighted either by the Cleveland Museum of Art (CMA) or by an outside rights holder, and thus there are restrictions on their use. The person using the image database is liable for any infringement.</p>
                    <p>The following are general restrictions on the use of electronic images. Others may also apply. Please request information from Image Services about restrictions associated with specific images offered by the library.</p>
                    <p>Access to high-resolution images is available only to employees of the Cleveland Museum of Art at their desktop, or to authorized visitors at the library’s public access terminals.</p>
                    <p>Images may NOT be transmitted over the Internet.</p>
                    <p>Images may NOT be downloaded for publication, scholarly or otherwise, or for any purpose other than teaching or research without written permission from the appropriate copyright holder.</p>
                    <p>Images may NOT be resold, leased, transferred, or distributed in whole or in part.</p>
                    <p>The Image Library database may be used ONLY FOR research, study, teaching, lecturing, and noncommercial purposes.</p>
                    <p>Images may NOT be modified, corrupted, or altered.</p>
                    <p>Images may NOT be transferred to other libraries, institutions, or persons.</p>
                </div>
            </div>
            <p class="lead">It is your responsibility to observe the legal use of copyrighted data or information. Abuse of these regulations and policies will result in immediate withdrawal of all ID and password access privileges, loss of library privileges and will be reported to the academic authorities or other appropriate authority. Visitors to the Ingalls Library and Museum Archives agree to indemnify and hold harmless the Cleveland Museum of Art, its officers, trustees and employees, from and against all claims or actions arising out of or related to such visitor’s (a) use of library or archived items; and (b) violation of this use agreement or any of the rules or procedures of the Ingalls Library or Museum Archives.</p>         

        {!! Form::open(['action' => 'RegistrationController@postWelcome']) !!}
          <div id="signature"></div>

          {!! Form::hidden('signature_data', '',
                ['id' => 'signature_data']) !!}
          {!! Form::submit('&laquo; Go back', 
                ['class' => 'btn btn-primary btn-lg pull-left',
                 'name' => 'previous_step']) !!}
          {!! Form::submit('Register &raquo;',
                ['class' => 'btn btn-primary btn-lg pull-right',
                 'name' => 'next_step']) !!}
        {!! Form::close() !!}
@stop

@section('scripts')
  <!-- Fire up the signature panel and inject it into the page. We don't
       need to rely on FlashCanvas because we do not care about browsers
      such as Internet Explorer 7 and 8 for this use case -->
  {!! HTML::script('js/jSignature.min.js') !!}
  <script>
    $(function() {
      $('#signature').jSignature();

      /**
       * Initialize the form so that when it is submitted the SVG signature
       * gets captured as base64
       */
      $('form').submit(function() {
        $img_data = $('#signature').jSignature('getData', 'svgbase64');
        $('#signature_data').val('data:' + $img_data[0] + ', ' + $img_data[1]);
        // Does anything else need to be done?
      })
    })
  </script>
@stop
