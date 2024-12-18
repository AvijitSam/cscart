<!doctype html>
<html>
   <head>
      <meta charset='utf-8'>      
      <title>LA GRANGE - Facture</title>
   </head>
   <body style='padding:0; margin: 0; font-family:"Times New Roman", Times, serif; font-size: 15px; line-height: 20px;'>
      <table width='100%' border='0' cellspacing='0' cellpadding='8' style='margin: 0 auto 10px; border-collapse:collapse;'>
         <tr>
            <td align='left' width='70%' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; line-height: 20px;'><img src="{{ asset('/assets') }}/images/Logo_la-grange.jpg" width='220' height='82' alt='' style='border: 0;'></td>
            <td align='left' width='30%' valign='middle' style='font-family:"Times New Roman", Times, serif; font-size: 30px; line-height: 35px; font-weight: bold;'>
               FACTURE
            </td>
         </tr>
      </table>
      <table width='100%' border='0' cellspacing='0' cellpadding='8' style='margin: 0 auto 10px; border-collapse:collapse;'>
         <tr>
            <td align='left' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px;color:#000; line-height: 20px; '>
               {!! Config::get('yourdata.page_header_address') !!}                                                      
            </td>
         </tr>
      </table>
      <table width='100%' border='0' cellspacing='0' cellpadding='8' style='margin: 0 auto 20px; border-collapse:collapse;'>
         <tr>
            <td align='left' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold;color:#000; line-height: 20px; '>
               <!-- Facturé à     --> 
               
               {{$fullname}}
                                                            
            </td>
            
            <td align='right' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold;color:#000; line-height: 20px; '>
               Facture n°                                                    
            </td>
            <td align='right' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px;color:#000; line-height: 20px; '>
               {{$invoice_no}}                                                     
            </td>
         </tr>
         <tr>
            <td align='left' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold;color:#000; line-height: 20px; '>
               {{$address}}, {{$village}} {{$postal_code}}
            </td>
            
            <td align='right' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold;color:#000; line-height: 20px; '>
               Date                                                    
            </td>
            <td align='right' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px;color:#000; line-height: 20px; '>
               {{$payment_date}}                                                     
            </td>
         </tr>
         <tr>
            <td align='left' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold;color:#000; line-height: 20px; '>
               {{$email}}<br>
               Tél: {{$phone}}  
            </td>
            
            <td align='right' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold;color:#000; line-height: 20px; '>
               Échéance                                                    
            </td>
            <td align='right' valign='top' width='15%' style='font-family:"Times New Roman", Times, serif; font-size: 15px;color:#000; line-height: 20px; '>
               {{$payment_date}}                                                      
            </td>
         </tr>
      </table>
      <table width='100%' border='0' cellspacing='0' cellpadding='8' style='margin: 0 auto 20px; border-collapse:collapse;'>
         <tr>
            <td align='center' valign='top' width='10%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold;color:#000; line-height: 20px; border:1px solid #999; background-color: #f6f6f6; '>
               QTÉ                                                  
            </td>
            <td align='center' valign='top' width='40%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold;color:#000; line-height: 20px; border:1px solid #999; background-color: #f6f6f6; '>
               DÉSIGNATION                                                    
            </td>
            <td align='center' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold; color:#000; line-height: 20px; border:1px solid #999; background-color: #f6f6f6; '>
               PRIX UNIT.                                                      
            </td>
            <td align='center' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; font-weight: bold; color:#000; line-height: 20px; border:1px solid #999; background-color: #f6f6f6; '>
               MONTANT                                                      
            </td>
         </tr>
         <tr>
            <td align='center' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; border:1px solid #999;'>
               1                                                  
            </td>
            <td align='left' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; border:1px solid #999; '>
               {{$show_description}}                                                    
            </td>
            <td align='right' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; border:1px solid #999; '>
               {{$slot_price}}                                                      
            </td>
            <td align='right' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; border:1px solid #999; '>
               {{$slot_price}}                                                      
            </td>
         </tr>
         
         <tr>
            <td align='center' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; '>
            </td>
            <td align='left' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; '>
            </td>
            <td align='right' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; '>
               Total HT                                                    
            </td>
            <td align='right' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; border:1px solid #999; '>
               {{$slot_price}}                                                    
            </td>
         </tr>
         <tr>
            <td align='center' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px;'>
            </td>
            <td align='left' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px;'>
            </td>
            <td align='right' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; '>
               TVA à {{Config::get('yourdata.tax_percent')}}% {{Config::get('yourdata.tax_percent')}}%                                                    
            </td>
            <td align='right' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px; border:1px solid #999; '>
               {{$this_tax}}                                                   
            </td>
         </tr>
         <tr>
            <td align='center' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px;'>
            </td>
            <td align='left' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px; color:#000; line-height: 20px;  '>
            </td>
            <td align='right' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 20px; font-weight: bold; color:#000; line-height: 25px;  '>
               TOTAL TTC                                                   
            </td>
            <td align='right' valign='top' width='25%' style='font-family:"Times New Roman", Times, serif; font-size: 20px; font-weight: bold; color:#000; line-height: 25px; border:1px solid #999; background-color: #f6f6f6; '>
               {{$slot_price_with_tax}} €                                                   
            </td>
         </tr>
      </table>
      <table width='100%' border='0' cellspacing='0' cellpadding='8' style='margin: 150px auto 20px; border-collapse:collapse;'>
         <tr>
            <td align='center' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px;font-weight: bold; color:#000; line-height: 20px; font-style: italic; text-decoration: underline; '>
               Conditions et modalités de paiement:                                                       
            </td>
         </tr>
         <tr>
            <td align='center' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px;color:#000; line-height: 20px; font-style: italic; '>
               Facture acquitée                                                        
            </td>
         </tr>
      </table>

      <table width='100%' border='0' cellspacing='0' cellpadding='8' style='margin: 0 auto 20px; border-collapse:collapse;'>
         <tr>
            <td align='left' valign='top' width='20%' style='font-family:"Times New Roman", Times, serif; font-size: 15px;color:#000; line-height: 20px; font-style: italic; '>
            </td>
            <td align='left' valign='top' width='60%' style='font-family:"Times New Roman", Times, serif; font-size: 14px;color:#000; line-height: 20px; '>
               Pénalités de retard (taux annuel) : 10,00 %<br>
               Pas d'escompte en cas de paiement anticipé<br>
               Indemnité forfaitaire pour frais de recouvrement en cas de retard de paiement :  40 €                                                        
            </td>
            <td align='left' valign='top' width='20%' style='font-family:"Times New Roman", Times, serif; font-size: 15px;color:#000; line-height: 20px; font-style: italic; '>
            </td>
         </tr>
      </table>
      
      <table width='100%' border='0' cellspacing='0' cellpadding='8' style='margin: 0 auto; border-collapse:collapse;'>
         <tr>
            <td align='center' valign='top' style='font-family:"Times New Roman", Times, serif; font-size: 15px;color:#000; line-height: 20px; '>
               EURL au capital social de 1 000,00 € - N° RCS 807 654 801 RCS Villefranche-Tarare - N° Siret 80765480100032 -
               N° de TVA : FR57807654801 - Page 1/1                                                       
            </td>
         </tr>
      </table>

      
   </body>
</html>
