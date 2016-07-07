<!DOCTYPE html>
<html lang="en">
  @section('htmlheader')
      @include('layouts.partials.htmlheader')
  @show
  <body>
  asdadasdasdasd
    <div id="example_wrapper" class="dataTables_wrapper no-footer">
      <table id="example" class="display responsive nowrap" cellspacing="0" width="100%">
        <thead>
          <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 75px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">First name</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 74px;" aria-label="Last name: activate to sort column ascending">Last name</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 171px;" aria-label="Position: activate to sort column ascending">Position</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 79px;" aria-label="Office: activate to sort column ascending">Office</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 28px;" aria-label="Age: activate to sort column ascending">Age</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 71px;" aria-label="Start date: activate to sort column ascending">Start date</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 59px;" aria-label="Salary: activate to sort column ascending">Salary</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 38px;" aria-label="Extn.: activate to sort column ascending">Extn.</th><th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 0px; display: none;" aria-label="E-mail: activate to sort column ascending">E-mail</th></tr>
        </thead>
        <tbody>
          <tr role="row" class="odd">
            <td tabindex="0" class="sorting_1">Airi</td>
            <td>Satou</td>
            <td>Accountant</td>
            <td>Tokyo</td>
            <td>33</td>
            <td>2008/11/28</td>
            <td>$162,700</td>
            <td>5407</td>
            <td style="display: none;">a.satou@datatables.net</td>
          </tr><tr role="row" class="even">
            <td class="sorting_1" tabindex="0">Angelica</td>
            <td>Ramos</td>
            <td>Chief Executive Officer (CEO)</td>
            <td>London</td>
            <td>47</td>
            <td>2009/10/09</td>
            <td>$1,200,000</td>
            <td>5797</td>
            <td style="display: none;">a.ramos@datatables.net</td>
          </tr><tr role="row" class="odd">
            <td tabindex="0" class="sorting_1">Ashton</td>
            <td>Cox</td>
            <td>Junior Technical Author</td>
            <td>San Francisco</td>
            <td>66</td>
            <td>2009/01/12</td>
            <td>$86,000</td>
            <td>1562</td>
            <td style="display: none;">a.cox@datatables.net</td>
          </tr><tr role="row" class="even">
            <td class="sorting_1" tabindex="0">Bradley</td>
            <td>Greer</td>
            <td>Software Engineer</td>
            <td>London</td>
            <td>41</td>
            <td>2012/10/13</td>
            <td>$132,000</td>
            <td>2558</td>
            <td style="display: none;">b.greer@datatables.net</td>
          </tr><tr role="row" class="odd">
            <td class="sorting_1" tabindex="0">Brenden</td>
            <td>Wagner</td>
            <td>Software Engineer</td>
            <td>San Francisco</td>
            <td>28</td>
            <td>2011/06/07</td>
            <td>$206,850</td>
            <td>1314</td>
            <td style="display: none;">b.wagner@datatables.net</td>
          </tr><tr role="row" class="even">
            <td tabindex="0" class="sorting_1">Brielle</td>
            <td>Williamson</td>
            <td>Integration Specialist</td>
            <td>New York</td>
            <td>61</td>
            <td>2012/12/02</td>
            <td>$372,000</td>
            <td>4804</td>
            <td style="display: none;">b.williamson@datatables.net</td>
          </tr><tr role="row" class="odd">
            <td class="sorting_1" tabindex="0">Bruno</td>
            <td>Nash</td>
            <td>Software Engineer</td>
            <td>London</td>
            <td>38</td>
            <td>2011/05/03</td>
            <td>$163,500</td>
            <td>6222</td>
            <td style="display: none;">b.nash@datatables.net</td>
          </tr><tr role="row" class="even">
            <td class="sorting_1" tabindex="0">Caesar</td>
            <td>Vance</td>
            <td>Pre-Sales Support</td>
            <td>New York</td>
            <td>21</td>
            <td>2011/12/12</td>
            <td>$106,450</td>
            <td>8330</td>
            <td style="display: none;">c.vance@datatables.net</td>
          </tr><tr role="row" class="odd">
            <td class="sorting_1" tabindex="0">Cara</td>
            <td>Stevens</td>
            <td>Sales Assistant</td>
            <td>New York</td>
            <td>46</td>
            <td>2011/12/06</td>
            <td>$145,600</td>
            <td>3990</td>
            <td style="display: none;">c.stevens@datatables.net</td>
          </tr><tr role="row" class="even">
            <td tabindex="0" class="sorting_1">Cedric</td>
            <td>Kelly</td>
            <td>Senior Javascript Developer</td>
            <td>Edinburgh</td>
            <td>22</td>
            <td>2012/03/29</td>
            <td>$433,060</td>
            <td>6224</td>
            <td style="display: none;">c.kelly@datatables.net</td>
          </tr></tbody>
      </table>
    </div>
    @section('scripts')
      @include('layouts.partials.scripts')
    @show
    <script type="text/javascript">
      $('#example').DataTable({
        responsive: true
      });
    </script>
  </body>
</html>
