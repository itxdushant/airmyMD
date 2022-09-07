<template>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="main_body">
        <SideBar></SideBar>
        <div class="right_section accordon-outer">
            <div class="accordon"
                v-for="item in items"
                :key="item.id">
                <div class="card-header"
                    @click.prevent="toggleExpand(item)">
                    <span>{{ item.title }}</span>
                    <div class="icon">
                        <i class="fa fa-arrow-down" v-if="!item.isExpand"></i>
                        <i class="fa fa-arrow-up" v-if="item.isExpand"></i>
                    </div>
                </div>
                
                <div class="card-body"
                    :ref="'content' + item.id"
                    :style="[item.isExpand ? {height: item.computedHeight} : {}]">
                    <hr />
                    <div class="card-content">
                        <div v-if="item.id==1" class="profile-aco">
                            <table>
                                <tbody>
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td>
                                            {{(patient.patient_profile)? patient.patient_profile.first_name:'' }}
                                            {{(patient.patient_profile)? patient.patient_profile.last_name:'' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{patient.email}}</td>
                                    </tr>
                                    <tr v-if="patient.patient_profile.location">
                                        <td><strong>Address:</strong></td>
                                        <td>{{(patient.patient_profile.location)? patient.patient_profile.location: ''}}</td>
                                    </tr>
                                    <tr v-if="patient.patient_profile.city">
                                        <td><strong>City:</strong></td>
                                        <td>{{(patient.patient_profile.city)? patient.patient_profile.city: ''}}</td>
                                    </tr>
                                    <tr v-if="patient.patient_profile.state">
                                        <td><strong>State:</strong></td>
                                        <td>{{(patient.patient_profile.state)? patient.patient_profile.state: ''}}</td>
                                    </tr>
                                    <tr v-if="patient.patient_profile.zip">
                                        <td><strong>Zip:</strong></td>
                                        <td>{{(patient.patient_profile.zip)? patient.patient_profile.zip: ''}}</td>
                                    </tr>
                                    <tr v-if="patient.patient_profile.height">
                                        <td><strong>Height:</strong></td>
                                        
                                        <td>{{(patient.patient_profile.height)? patient.patient_profile.height: ''}}</td>
                                    </tr>
                                    <tr v-if="patient.patient_profile.weight">
                                        <td><strong>Weight:</strong></td>
                                        <td>{{(patient.patient_profile.weight)? patient.patient_profile.weight: ''}}</td>
                                    </tr>
                                    <tr v-if="patient.patient_profile.gender">
                                        <td><strong>Marital Status:</strong></td>
                                        <td>{{(patient.patient_profile.gender)? patient.patient_profile.gender: ''}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else-if="item.id==2" class="allergies-aco">
                            <div class="allergies" v-if="patient && patient.patient_allergie.length > 0">
                                <div class="allergie" v-for="(doc,index) in this.patient.patient_allergie" :key="index">
                                    <label><strong>Type: </strong></label> <span>{{doc.type}}</span>
                                    <br/>
                                    <label><strong>Name: </strong></label> <span>{{doc.name}}</span>
                                    <hr/>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="item.id==3" class="medical-history-aco row">
                            <div class="col-md-4">
                                <label class="container">Alcohol/Drug problem
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Alcohol/Drug problem') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Emphysema/COPD
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Emphysema/COPD') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Liver Disease
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Liver Disease') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Anemia
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Anemia') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Heart Attack
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Heart Attack') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Anxiety
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Anxiety') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Osteoporosis
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Osteoporosis') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Neuropathy
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Neuropathy') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                                
                                <label class="container">Athritis
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Athritis') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="container">Asthma
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Asthma') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">High Boold Pressure
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('High Boold Pressure') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Heart Murmur
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Heart Murmur') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Seizure Disorder
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Seizure Disorder') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Atrial Fibrillation
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Atrial Fibrillation') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Migraines
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Migraines') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Hepatitis
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Hepatitis') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Dementia
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Dementia') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Diabetes
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Diabetes') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="container">Diverticulosis
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Diverticulosis') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Cancer
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Cancer') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Colon Polyps
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Colon Polyps') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Hysterectomy-partial
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Hysterectomy-partial') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Hypothyroidism (low)
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Hypothyroidism (low)') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Positive TB
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Posotive TB') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Stroke
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Stroke') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Abnormal Pap Test
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('Abnormal Pap Test') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">High Cholesterol
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.medical_history && patient.patient_profile.medical_history.includes('High Cholesterol') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>

                        </div>

                        <div v-else-if="item.id==4" class="surgeries-aco row">
                            <div class="col-md-4">
                                <label class="container">Appendectomy
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Appendectomy') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Tonsilectomy
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Tonsilectomy') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Cardiac Bypass (CABG)
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Cardiac Bypass (CABG)') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Hernia Repair E
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Hernia Repair E') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="container">Hysterectomy-Total
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Hysterectomy-Total') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Gallbladder Laparoscopic Tubal ligation
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Gallbladder Laparoscopic Tubal ligation') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Vasectomy
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Heart Murmur') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Neuropathy
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Neuropathy') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="container">Gallbladder Open
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Gallbladder Open') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Cataract Surgery Right
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Cataract Surgery Right') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Prostate Surgery
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Prostate Surgery') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Kidney Disease
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Kidney Disease') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                            </div>
                        </div>
                        <div v-else-if="item.id==5" class="medication-aco row">
                            <div v-if="patient.patient_medication && patient.patient_medication.length > 0">
                               <table>
                                    <tbody>
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td><strong>Reason:</strong></td>
                                        </tr>
                                        <tr v-for="(medication,index) in patient.patient_medication" :key="index">
                                            <td><strong>{{medication.name}}</strong></td>
                                            <td>{{medication.reason}}</td>
                                        </tr>
                                    </tbody>
                               </table>
                            </div>
                        </div>
                        <div v-else-if="item.id==6" class="health-aco row">
                            <div class="col-md-12">
                                <label class="container">Sexually Active
                                    <input type="radio" disabled :checked="(patient.patient_profile.sexual_health && patient.patient_profile.sexual_health == 'Sexually Active')? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Not currently Sexually Active
                                    <input type="radio" disabled :checked="(patient.patient_profile.sexual_health && patient.patient_profile.sexual_health == 'Not currently Sexually Active')? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Never Sexually Active
                                    <input type="radio" disabled :checked="(patient.patient_profile.sexual_health && patient.patient_profile.sexual_health == 'Never Sexually Active')? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>

                        <div v-else-if="item.id==7" class="health-habits-aco row">
                            <div class="col-md-12">
                                <label class="container">No Exercise
                                    <input type="radio" disabled :checked="(patient.patient_profile.habits && patient.patient_profile.habits == 'No Exercise')? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Mild Exercise
                                    <input type="radio" disabled :checked="(patient.patient_profile.habits && patient.patient_profile.habits == 'Mild Exercise')? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                                <label class="container">Regular Exercise
                                    <input type="radio" disabled :checked="(patient.patient_profile.habits && patient.patient_profile.habits == 'Regular Exercise')? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>

                        <div v-else-if="item.id==8" class="surgeries-aco row">
                            <div class="row">
                                <div class="col-md-4">
                                    <span><strong>General</strong></span>
                                    <label class="container">Fatigue
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.general && patient.patient_profile.general == 'Fatigue')? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container">Fever
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.general && patient.patient_profile.general == 'Fever')? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container">Weight Gain > 10lbs
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.general && patient.patient_profile.general == 'Weight Gain > 10lbs')? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container">{{'Weight Gain < 10lbs'}}
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.general && patient.patient_profile.general == 'Weight Gain < 10lbs')? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <span><strong>Skin</strong></span>
                                    <label class="container">Rash
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.skin && patient.patient_profile.skin == 'Rash')? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container">Nail Changes
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.skin && patient.patient_profile.skin == 'Nail Changes')? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container">New/Changing Skin Lesion
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.skin && patient.patient_profile.skin == 'New/Changing Skin Lesion')? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container">Hair Loss
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.general && patient.patient_profile.general == 'Hair Loss')? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>   
                            <span><strong>Eys/Ears/Nose/Throat</strong></span>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="container">Vision Changes
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Vision Changes') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Decreased Hearing
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Decreased Hearing') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Ear Pain
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Ear Pain') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Ringing In Ears
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Ringing In Ears') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Nasal Congestion
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.surgeries && patient.patient_profile.surgeries.includes('Nasal Congestion') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container">Nose Bleeds
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Nose Bleeds') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="container">Hoarse Voice
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Hoarse Voice') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Sore Throat
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Sore Throat') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Sneezing
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Sneezing') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Sinus Problem
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Sinus Problem') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Lump In Neck
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.eyes && patient.patient_profile.eyes.includes('Lump In Neck') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="item.id==9" class="tobacco-aco row">
                            <div class="row">
                                <div class="col-md-6">
                                    <span><strong>Cigarette Use</strong></span>
                                    <label class="container">Never
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.tobacco && patient.patient_profile.tobacco.includes('Never') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Former Smoker
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.tobacco && patient.patient_profile.tobacco.includes('Former Smoker') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Quit
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.tobacco && patient.patient_profile.tobacco.includes('Quit') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Current Smoker
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.tobacco && patient.patient_profile.tobacco.includes('Current Smoker') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <span><strong>Other Tobacco</strong></span>
                                    <label class="container">Pipe
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.tobacco && patient.patient_profile.tobacco.includes('Pipe') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Cigar
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.tobacco && patient.patient_profile.tobacco.includes('Cigar') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>

                                    <label class="container">Chewing Tabbacco
                                        <input type="checkbox" disabled :checked="(patient.patient_profile.tobacco && patient.patient_profile.tobacco.includes('Chewing Tabbacco') )? 'checked': false">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="item.id==10" class="tobacco-aco row">
                            <div class="col-md-6">
                                <span><strong>Drink Alcohol</strong></span>
                                <label class="container">No
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.drink_alcohol && patient.patient_profile.drink_alcohol.includes('No') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Yes
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.drink_alcohol && patient.patient_profile.drink_alcohol.includes('Yes') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">0-1 Time/Month
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.drink_alcohol && patient.patient_profile.drink_alcohol.includes('0-1 Time/Month') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">2-4 Time/Month
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.drink_alcohol && patient.patient_profile.drink_alcohol.includes('2-4 Time/Month') )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div v-else-if="item.id==11" class="tobacco-aco row">
                            <div class="col-md-12">
                                <span><strong>Have you used recreational or street drugs within the last 2 years?</strong></span>
                                <label class="container">No
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.street_drug && patient.patient_profile.street_drug =='No' )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Yes
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.street_drug && patient.patient_profile.street_drug =='Yes' )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>

                            <div class="col-md-12">
                                <span><strong>Have you used recreational or street drugs with needle?</strong></span>
                                <label class="container">No
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.needle_drug && patient.patient_profile.needle_drug =='No' )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">Yes
                                    <input type="checkbox" disabled :checked="(patient.patient_profile.needle_drug && patient.patient_profile.needle_drug =='Yes' )? 'checked': false">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <!-- <div v-else-if="item.id == 12" class="insurance-aco row">
                            <div class="col-md-12">
                                <table class="table" v-if="patient.patient_insurance.length">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Provider</th>
                                            <th>Phone</th>
                                            <th>Group Number</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="patient.patient_insurance">
                                        <tr v-for="(insurance,index) in patient.patient_insurance" :key="index">
                                            <td class="insurance-img">
                                                <a :href="insurance.image" target="_blank"><img :src="insurance.image"/></a>
                                            </td>
                                            <td>{{insurance.provider}}</td>
                                            <td>{{insurance.phone}}</td>
                                            <td>{{insurance.group_number}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <span v-else>Insurance not found.</span>
                            </div>
                        </div> -->
                        <div v-else>
                            {{ item.content }}
                        </div>
                    
                    </div>
                    </div>
            </div>
        </div>
    </div>
</template>
<style scoped>

.accordon{
  width: 80%;
  height: auto;
  display: block;
  position: relative;
  margin: 8px 0;
  padding: 0 10px;
  border: 1px solid #aaa;
  border-radius: 4px;
}

.card-header,
.card-content {
  margin: 10px 0;
}

.card-header {
  cursor: pointer;
}

.card-header span {
  font-weight: 600;
}

.card-body {
  height: 0;
  overflow: hidden;
  transition: 0.3s;
}

.icon {
  float: right;
}

hr {
  margin: 0;
  height: 1px;
  display: block;
  border-width: 0;
  border-top: 1px solid #aaa;
}

/* Customize the label (the container) */
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
<script>
   import SideBar from '@/Components/SideBar'
   import UserBar from '@/Components/UserBar'
   import LaravelVuePagination from 'laravel-vue-pagination';
   

   export default {
      components: {
         SideBar,
         UserBar,
         'Pagination': LaravelVuePagination
      },
      props: ['patient'],
      data() {
        return {        
        items: [
            {
                id: 1,
                title: "Profile",
                isExpand: true,
                computedHeight: 0,
            },
            {
            id: 2,
            title: "Allergies",
            isExpand: false,
            computedHeight: 0,
            },
            {
            id: 3,
            title: "Medical History",
            isExpand: false,
            computedHeight: 0,
            },
            {
                id: 4,
                title: "Surgeries",
                isExpand: false,
                computedHeight: 0,
            },
            {
                id: 5,
                title: "Medication",
                isExpand: false,
                computedHeight: 0,
            },
            {
                id: 6,
                title: "Sexual Health",
                isExpand: false,
                computedHeight: 0,
            },
            {
                id: 7,
                title: "Health Habits",
                isExpand: false,
                computedHeight: 0,
            },
            {
                id: 8,
                title: "General Health",
                isExpand: false,
                computedHeight: 0,
            },
            {
                id: 9,
                title: "Tobacco",
                isExpand: false,
                computedHeight: 0,
            },
            {
                id: 10,
                title: "Alcohol",
                isExpand: false,
                computedHeight: 0,
            },
            {
                id: 11,
                title: "Drugs",
                isExpand: false,
                computedHeight: 0,
            },
            // {
            //     id: 12,
            //     title: "Insurance",
            //     isExpand: false,
            //     computedHeight: 0,
            // },
        ]
        }
    },
    methods: {
        toggleExpand(item) {
        item.isExpand = !item.isExpand;
        },
        getComputedHeight() {
        this.items.forEach(item => {
            var content = this.$refs["content" + item.id][0];
            
            content.style.height = 'auto';
            content.style.position = 'absolute';
            content.style.visibility = 'hidden';
            content.style.display = 'block';

            var height = getComputedStyle(content).height;
            item.computedHeight = height;

            content.style.height = 0;
            content.style.position = null;
            content.style.visibility = null;
            content.style.display = null;
        });
        }
    },
    mounted() {
        this.getComputedHeight();
        
        console.log(this.patient, "this is testing");
    },
    }
</script>