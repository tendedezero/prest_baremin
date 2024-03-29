<?php
/**
  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2018 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */

require_once 'Customweb/Payment/Authorization/AbstractPaymentMethodWrapper.php';

class Customweb_SagePay_Method_DefaultMethod extends Customweb_Payment_Authorization_AbstractPaymentMethodWrapper {

	/**
	 * This map contains all supported payment methods.
	 *       		   	    	 		 
	 * @var array
	 */
	protected static $paymentMapping = array(
		'sagepay' => array(
			'machine_name' => 'SagePay',
 			'method_name' => 'Sage Pay Payment',
 			'parameters' => array(
			),
 			'not_supported_features' => array(
				0 => 'ServerAuthorization',
 			),
 			'image_color' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAApCAYAAAD+tu2AAAAFlklEQVR42u2b+2tUVxDH/Z8iFPpDQRAKQn8QCj6wBWlRpCiCEBQVBGkFFVpaaREEEUUQRBFBEEWNtqnWt1arrTY2rdZXjEbjM3rkc+G7zJ499242e3c3LHNgyO7euffMme+8dzNlTt/U4NSt1DMwxZXgADs5wE4OsJMD7OQAOznAjdGy0586wN1MfXf3O8AOsAPsADvADrAD7ABPToC/+GVaWHtxYfjp2poKrTz3Wfj8xIdVfAv6p4c1F74Mm/5YVcinZ667tDjjWX95aZj/80fZ58vPzs32guDRc+3+8JQB8JJTn4R5xz+ovOe59eS28iO3+L+90hu+Ojmjcp17l/42s658nHvilX6TALP5oTu7w+u3r0Jqjb4Zya6jmAP/7czl43Ou8zyUcGGov4Zn5PVw+P7qinBl+EzlMxS4b3BrGHs3VsN/6+n1DPRmAL7/4k62L0Ccfni0Zg+ux3sAxuVHp3LPueefLZnRYOSsv55cqjKi2CGGXt7L+L6+uKi9AGOB1x6fD2UuDjv86mEhjwUzNhjevxgbreLFc5oB2O55e3QgnLh3IDMeK4dVPtdZyMF5eN9//2DlWSw8GmOW/Juvr03ujzHIuPOMoGUAEz6tIvEuQtqiXz/ODrzz7x8ysCCsHOVAWC4eAS+f7xr4Mdfa8U68FJBSXq11c+RqWH1+fkW23jOzKl6EcmxonAjAgBWHSWSXMXEuqxeikdKHdYjBZzeq+GUMyJra/+7zwez6kf/3tj9EW2DODR1P8mB1AK5wk5ezuD9eW/78ptCobOjWHnH6kIL2/7u9KYDz7scAtcjJjeiM9xt/X1ZxEPRjeUlrWtQtbQeYMGMX4ZpDxpY7Htp9a3MNcHmFkkDTIvzlPRdgJFszACNf6jqRQmvbjY3JHEoEwlh5hjxWAGPw1CmpMK3wTA7uSBUNkLGy7SI0UxApB2KtJx8czvIXirOkQ9rwnJdzbJGlQofPUkQoVIhtBcBECS3Lg3HGcsZLvITfVBSUbgn3HWuT8qrLVLXZyII/b08MZCKrFQBDMcDUGMrN3E8XgXfyOV4ey0O9IqO2rWDz4bnEPlihiLBNnjl4e1eydeHgHHjHze+qeubYSPIAJqTFzwVwQCqioiKlLA+mqMTgJR86iKMQhVkMMDxqhTACm7Lw4o4OOooafara2HPz2gGFKWsIqVxOjo8XYb9Vk6x6AONdWqQgeaj1xnoAQ4RhFoZuoxR5uGMAIywVLEKkwMCruV4PYBRhe1ctChKrJCwdMFLtlG2RbPrAcCiEmgU4T9EqmpABHcjzqClSNUQqRMsZZNi2cGtkIlcqwFiuDZUIhvWhCA6JRabyLnx4HAclNFNdplokOwnjOvcUDUCQBR72RgbukXwov2jYMd4+mD7fgsYZtIcMQNMpDTNsQUpasjpjDpAqqhiOxL11RzyYA9SbOtlqumhZT48rarvIVcpXrHrPVdhLhcuJTLKQEwCsjAChVMVfWwQiK9d1P8MavaaFs/272iItapmOf9nAgQCaXhQLRHiI1xwGK9fhyVEAovEcymICxf22D+a15YXgUyqw41HSBNGE/S3wvCZ8ljGLFhi2hxXomp/be5iawWu9VRM8DTvkGDayEJqtw4zni4iu+7oQi7fz51TubWWRpW+tyI1FBaYFu5EZMpW4OoOu/j6Ylise3dnJlPLieJTcyiq6bCJSqeXqSoAJwRRLysWEQBVjca9clhImC8CqpAntqdl6VwBM+CsqsmwL1Y5fdLQSYMI8OZgoRM2hKrrcX5hMwhCN9TIFSvXG5CaU0a6f7LQS4FRPT5GVSk1dWWRh2YQtAOVveWGrNq/nXaNiB+TUN0XNEtW/vgyho6ATKP+M/qM7/9cVJwfYyQF2coCdHGAnB9gpBnj2sZ6tTt1Jc/t6NrwHM41x1MNaOtcAAAAASUVORK5CYII=',
 			'image_grey' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAApEAAAAACLTW4fAAAE8klEQVR42u2ZTUsqXxjA79bFXbZpMQgJhas+QNBCGNrFrP0AbbNd0SL6COKgYkQqYfh6kQkRL3Eto3whqYgk7IVLEeU/6fqSJOrcnh7OPTNmNWbIH5pzoPLMnGfO73kf+2ae+kpz5fs3FVgFVoFVYBVYBX59ure+GPDxkAqsAqvAKvCnAtvHg2zMAHPtgU/gmmPAb4py8jW488evmCHMWyPmKY82yAZZ+zjci/s92veBVyYsY/Dbo22XjfLDPKyvl5dn4DOfcN61y7NG3sr77wJbI4fGhkv8Nx6HD40ebbYpXWu4sk1rZHnmYpOs1LjI7qUW/grzmcWWnqwXnlTwNnCJqXHOu9MzsqPEkB3urd826TNTActYlBPF6zSqiBiiXBHF0OAHgfnEVVVUNK7T1Xn5CmISxTRc9RCurpffBsadRXfOV2BRBh4/5xPFeug6nfOdLMBdohgzWCMg/2eG7k8FQN1SFXQF/OMXHjKyuzKxdBsa3J6rzlfng2zRXXRHnyyxMhFkdyales8shvn1MrU1jJtprwOkrdrARjUO3fF14HqIOGWQBTUV3XiWbBMCBA3x3x6ugxp+2+j++1lRPBr9sEsjzLlAVyxjS7fgONLYOhcI2oZTrip0b9hBAgQOtDfyNrD0emYRZKw9dD6ZeUp49gjHAK56nsPIb/owcMyAh76qrj0Q7b6cSR2Bk6YlQINxsiC9d28EpL0NnNTRz6vPcRsXaYyG+Q1nUgeWBWA+8ThMnRoculzpIUvbx8mxcVTnL7UQgwKT1xTYEoMTHokOLY0dTFqQdi61dBbd4LLKga0RkIErHi2RSQasHo1SH4TTZps9lSVpxqSZs3PiKjHSnQX29RSnHNg8RYCjHMRziTk0/sxEufg/OaFBUDUWwvccWmEdBjeKGXYm9w9ogamHDo1bf7A6E5VIgfkEubfAHg/JpzypKLHw9pzzDuTtHxAfCrIE2DIGhSjKYWjdz/bYeMgLv9dBrCstBeBUqAQa52sPRDV5TTedVjuw3wQyBCYuEju2A5unsk1RPD1Dn0oFegIOsjUuFaAYjoEa1w5sjWCFhZHz4ZEsY8dDtFRhUcIAORpdtb0HLD00JKeGyz4O1nscpjkiLgkNrwOUjemtczenENhvQresh07PUoGkLtuk0VsP5TXxp9K/4aRFCTuxcyGvkTchLX1ek9SlAucCyGu45K1Hpzoc2UW0+HMTAgqIcthqYCrd+oMnW5mgyeo6TSp2DxaOGdr7J8zU8hW0OsnVOMoViCxRbL8XnI865uudVo27TqPEohvCik9gEixXim64frEJP/dGsMZDOYKxM9nzywOfiBlOFu5nW/qW/n72YjMuwuOFp1IDTV2Nu5mOGbAOJ3W42nDdTEMYYFMaZP2mkwWEL1dyvvd7acDAKgvo0KXjleWZnA/tCl0etB5gDPSWVRsa5uWrRN9eD5dusYum8dtd0oI3LI9WnjIRu3OfvD0H9aDP78NhnrR42FFBNL48cndZWum8mYbi1Udg+3heA3GcbUIiI5VZyRE+AxjydEtPe/Y+AAdZedLCItX9Nx7dAXueGl0+ITCQpZV9c/KJLr10u39AK3KBFZiPfMXTHTCt9tV5Gk59TFp8wusQGK9DiXPRaJd+uqqWmLiodK/fBC8jNe5kQekT1X+1qMAqsAqsAqvA/y/gmOYrzYTxLx1unu/xCBLlAAAAAElFTkSuQmCC',
 		),
 		'creditcard' => array(
			'machine_name' => 'CreditCard',
 			'method_name' => 'Credit Card',
 			'parameters' => array(
				'CardType' => '',
 			),
 			'not_supported_features' => array(
				0 => 'PaymentPage',
 			),
 			'image_color' => 'data:image/png;base64,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',
 			'image_grey' => 'data:image/png;base64,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',
 		),
 		'visa' => array(
			'machine_name' => 'Visa',
 			'method_name' => 'Visa',
 			'parameters' => array(
				'CardType' => 'VISA',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '4',
 				),
 				'lengths' => array(
					0 => '13',
 					1 => '16',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'Visa',
 				'cvv_length' => '3',
 				'cvv_required' => 'true',
 			),
 			'image_color' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAnCAYAAADEvIzwAAAK/0lEQVR42sXcXUwbyR0AcJL0EiVNT0RRJErjaLszaww2xMY2xthgJwYMoS1VFXSXu5IDUuJ8mBxxyPFxZ+KQAA4Q+5IQSBzugJArUqQ8VIpUqbzxhtQHv/GI1Ace/ejHaWfsHMV4d2d2veQs/aUIZf2xv52Z/8x/douMxo7jHNdVLBlmehQVRQ4WafA67bx9lDP3F9OiqKPjUO6RkYOcOVIsGd5sqPmep52xo7x7Qg/dE53QFY0J7kerwPVoHbim1oF7ah26p/8J66fGhYaZvwgN8XLY+vRIkSav/x6w/vHlMc67WLwTf84P6tvw8LNNHnyOeEjiEo4vEC+Q+BLHXxGvJ9GZjbLLOL7C0YV4A4nubJT34LiyajTeOF7ITwJVt7bA2a9RNvoRMJO4jSOEgIXEHRwDiFwIu4/jLHc9wDqIgG0IxzCOEQTsJL7NRs13OMKIYLF8D6838ivoHPdD18MkrJtA0EViEkcUQfejDzGFMCyOaRwzCDaQeIwjhqAnloSeeKfV+vITNedBfz7Rqm98mRIaE0hoevUhFpDQTOIHHD8iwb+Yicq2n05Q3zDTioXPHRz8IoaB0yqBSWzvPfmsL2jqAxgY0YB5853ZvccK1qEwA3CKpdUIzgcB6HyIYN34h1AFjCOO//70lOLz0DT3J6HxJcoGHVhofu1Q+hkHIPwMYNioCmAEy69E1bXevgQLMLCHdHnHWge3aMC8fXRW7vMNtREOOMe2oPMB0gqYXDBKzgHnjRcLjS+QEmDoXx4soNPsOATh5XaMu80KDCr+hpSOdRD2HcHAiAYMLXeSe4+1Wq9+ksGlAEPbaLvU5wv2iBnWjmFYEtoAA098TfFF7ns+phRY8C9taDDgdxzCLXmVFZgzdpUoAq7qu8gCzFtCvryr3jzEsQDrXPdKRXFrH5TD2vtIa2Ch4XFAyTkg4zX0zSMVwKgook2SW8TrL68xARt6PQqBNxmA02I9A7QNtrMAk8QpP/eIHMa4qX0B9sbNSs6B0DjXphaYu7BYogkwJ3SZWYB5Yy/zuABMN3SgKohowLj1hkSPt3+zRAOGjvCm6IXliMxmcZUCT/0buGa25IBL8RRHWff8YkstsL5lyacJMM62D7MAg4redeZeoTIYYwHOzH/FToz1mxQNmK/5bjB/3H96BDruIUbgJKgbN5EWL9a1ct6pEsEz04SB/5EFjqcU9Yy+WT3BVQ3sX4wWafXCwOsMwIhk4/QLJnIYVBJcCrDl9jux40utkWMYGFG7aFvYldcl1oy2MQL/S0k2TObaZfXfVynqnn1zq4UA49jSELirhwX4TOV16gScN19vYwEWqvvLRYcM+10zC/AZ99AJ0e6ZAbi8fvy3Rfv4MnqfH4e+OVQQcMsSUruoIjKl+QqwAHPGXmqSAU03NxmAJa9OUD0QYAEWa4EYd4OpBTsin+4nsP78fFAOWN/0KqVvXAjSgA0ti5wmX4hkoyzAoOKq7DRBZwmWgsqbiAYMLf0XpYEH1+jAI+/EEyyMywAsOKOBfdPF0xt4/nlKHjgRKm9eMNGAoX+5Xbtu2tD1ngaMM+lV2ayxMjjGAiyW2Py84gaqCa48MG8b6ZEA3mBNsnj3ZM9++IJzz1wYGMkBc97FEjKuU4FblmY1BO6+RG3Bxt60XC+QxZUHhpbbktkhtAyfYgHG/zZJTpHYs2gEXJNrsH7ilJbA8PzsBgV4Z3qnb1pIyQHrW5ZT2l15oFtH76J7kVThARqDfhZgnERJTuAzFSQGYKnvAGwRkxLgnXmwaypGsvdCz2FZ07PSDK4MMPS92hmecAtekgMWWpbR6Y63R7UyPsACzBt69eLJ1fUkDRie7ZddYxWsA2EG4JRc5Qi34qS6hQ4S0yGx1TEFrTdKAyYZ9s7/979qpwELba/LtRyHV2nAsCKQN/BzxhslwHQD0YC5qq9ly2Cg+u4WDZi3j8iOS8D+UKceeAqB+ultwTtlVnrujB2RwxgYUYBzihUkS6YBQ//rTu3Gj7LudmoLLr+aX7utvBZmAE7JVaQyFaTqu4gGDG3fUjNL3nnfp8FadEzRufM+vUgDLmuaz1mcITtFqC24eWVVM2BSMaIB46lS7hy2o+MQMF1PMwDLTk04cz/HAqyziFeQ8lpy7Zir4GKDe2aWtaqDs+dtGrBY94+Bt2SBW16nNU3zMW6aAoxIa9uBqbzuwcCIBkzb+gPNA+0swErGSGiZOAXrxjYLqia5H1OnKsA7b4LnCK40MPS9FH0fnElHKcAItr7RbmEGGHoSNGBdWbD0/8nVtQ0qcFX/EvVzLQNLNGBoH9lUs9GNdz68hIHTasuFBm+co7TedzRg3pcQTU4NjT/6acDChZ/M2nXThm4/DZivCPiyLaTvFDBdQzRg/mxITwWuHkjRgHnriOqtLJnie93DkKp6sCeWlHrfM+65E/DcMyQHrG98kZIqbpC6Lw1Y37oS1Az4d4bLJ2nAgvFqOFsWDAzSgGHVLWqry1SQqgcQtYu2DbsK/X1kBQkjR5UW/HdPb3KGAd+TEBW46UVIemPN20P0FvxmTeNxuCclBwyNgQ2SEQPjtTQNmDf3tVFbr23AxAJsqBk+qdlvdE/ogWtymxWY7I8Ww4HnnqZpwJz3uezuDH3zD0lZ4NYVxZv9aN10TA4YGANkutSGgRENmCUpAmcHAizAmv7ITPc6eQIDp5m27NTH8wok/LknPgyMaMBqy4W7gQ2+5ZNaAntowDjSNGDh7K0wU2JXPfCeAfj9fhQHoDsaYtuTNdOZP/d9kvxYwGWtb1za/Wj45acMwIgGbKi5yXLVHSB3NtCBh/elxIcTrotsLTiWs+kQNDzWYWD0sYBh68qgtuNwec92gcBMiQEZV9mAxStI2dUx9VtMgSu6xgK8d6qEYRMfE1i48HpTU+Dfl/WMFQRcecvKdIJt/S4WYKlqD2cfMUPHaIp3jl4yeiOK7qMSXNEAY5KV3r2ilcnECe7HBCaJFk7qtBuHK7odBQCTig9TQiRYQmEqsH04JX2BhIMYGP28mwM4ImvQcb+Tr7mnL3M9+k3uSYkcrGqe/jXvHPcB18Q6+zQpnlPD5j3xS78EMHfhbYlmwKXWq8fUAkPTLeYKCLTc2aQDDyYkgR3htd3AqurBtJUs3+zJnJKk9/vULwEs+FfaNB6Hr2ypAWa9GzFTQfpw+6gcMLnTQWoZktxCup/AfMPjnK095O4GDIyowI1zUb5xvocEzIlED2xeyA//wiANWN+6EtUaeFA5cDDBnODYQzoWYGD/Vie5xWcfgUHDzPu9c2/gebLGAJxSM2cXmpfSsi249c2WpsBkm6xiYNMNHXP3bL7dzgK8u3qVcwHaR337BQzcGHdPqZB01dBDcGnAc6qmNELz4ioFGGn3tIGiD7e1KACGlTeTiqYo1aEEDRjahjalx9/Rsf0A1tdPiy7uC554mAWYtjQpecH7FztpwIaWVU7jdekrSVZgvuqmohumgCWUogEL9iHJ1TBYE05qCQzc0+/I8qXYZ2UeAeGJp2nA4Pxz1d0of2FZT23Bf3hzUdv5sOFKkBE4nf8gFfksffczOqS7aPkKEnDegxh6BDoi/1EDDFzRbYzbSbvbQaifbss8woHagudV76E67Xx7lAYstP59SVNgcuXmPoFH/Ck5BEzZO5On6Ii9V+7TdJSsUmXvLoycJtt1hNpIl+B4EIbO+zFY+yABneMxUibEc+AeWBdtN9ROckrGs9LM03HixbQo9MZtsafu7A6j9+3x/wHJwwyjqYbGUgAAAABJRU5ErkJggg==',
 			'image_grey' => 'data:image/png;base64,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',
 		),
 		'mastercard' => array(
			'machine_name' => 'MasterCard',
 			'method_name' => 'MasterCard',
 			'parameters' => array(
				'CardType' => 'MC',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '2221',
 					1 => '2222',
 					2 => '2223',
 					3 => '2224',
 					4 => '2225',
 					5 => '2226',
 					6 => '2227',
 					7 => '2228',
 					8 => '2229',
 					9 => '223',
 					10 => '224',
 					11 => '225',
 					12 => '226',
 					13 => '227',
 					14 => '228',
 					15 => '229',
 					16 => '23',
 					17 => '24',
 					18 => '25',
 					19 => '26',
 					20 => '270',
 					21 => '271',
 					22 => '2720',
 					23 => '51',
 					24 => '52',
 					25 => '53',
 					26 => '54',
 					27 => '55',
 				),
 				'lengths' => array(
					0 => '16',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'MasterCard',
 				'cvv_length' => '3',
 				'cvv_required' => 'true',
 			),
 			'image_color' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD0AAAAyCAYAAADvNNM8AAAElklEQVR42u2ZP0wTURzHH4h/U6QGkHp3xMYwEGVA4+BA4kUHB0wYHDTBiEljSCSmJsYwOKBxINFEBvyTWNobHBg6EMPg0MGBwYGBgYGhA4MDkVarAu3Vozx/r/0dnqXCcfeDGr1f8g1H3rvX93m/9+79fu8x5plnnnnm2X9qaSa1LzIplGLSECiSZrKWYsrTNFMG4a/KGauz0w5/0ezTY62Xcpo0lI0pozlN1op/Y/KDbEy6mo3ISlVBF1kwAKDDKSYnQXwLLYHGF5ncWaktfUzuBrAEyADxLfRB15Qw14IHdhG22VfyaBGEO9DkZ1by2LImd+Zi0rQN0Er6COrbceAFFgiCt2Ydwq4rXSdljTv1b6HTBYfA69KjyoRYFjvkYakLPLzgFjhVAx1VD3Lex7gxVM/dQqNml7VggBhYaYMOp1wDg/TzJWBTxuMGEnA9Js+Qeby0huU5CuDlkw2/ARd1k/H8SDMVeJwEGjo7QgH8pb6Fr12v2QgNWuvfw3NjEgm42NoopnWOZFqrBysCr0/zR4ep1neSP7QXF/zBy1KEysubARe9fQu8HZWppnm/I2ARRVF9vFZO128JLfTjSSOVtxMOvayoFMBCxuV9tqBXBw9RQRuOvuQQhDygAE7vkWwBF6f47b1U0Dwfk3ocJBEiaXAPnWlstg0tti8qaEfrGj5i7yigvwaa7EOD9NfHaMAhU3OyP7+vCvTLAI2nNXnEyfSOVwOaatvKaspg9SIxX8D+hyxUS7amHaWdaSYNUG1Za701tqAL4f1k0CvR1rPbhhaJPhV0/sIBW9DGEFUoKi24STZmKKC/nzhi7yM2epQKOuIGup8qQClcq921qS2OoRxDY/ydJMmlTzVsCp1/1kQDHZXHKfLpbpK1XSPz1Z69lWPu+2Qx95I+prRRnY8Nk2xf/qMbvuTiAIEqCnN9gFBhmk9SgH9TGjm/8SuH1p+3VC/stAdOc6jwTWosFEJ1P/QXLWsUaWQ2Kt/b4cN+Jez2CEmEuCt3j58RtxVu92NxDbRrVzrodWObwDOf2K9OirMskQLibcV20saMmM47dsi/VdSG4WpiE+/PwewYhbrn/rh0AF4k/QD0ZpMBWBLbUS6q9Ga0oP+vub3MsKBf3GCKo6bSTaYzTwio/FhrR05TVLEFVcWjnjkzETB07sLvDFL9jrgYC2LHzUP0jgpQ4r7YXLfiWaR2PnweBmmWcrMNs712UBO+Y75v7bwf+9COz+X1zXLxGyoFdF/xlpaxWdAcaBr/TmK5yGiSqHkEmcMOTOCgTYFmQE/xnWl8fx4HRsP3xWHexWL0y5iIocOgbtASvp9CKLP+AJZnsM0UJfQ4wkxhx8SluoGjnUSwLnyuQ8/G0SNmG2bEpGInzY734nMIy+NY37QE1vEhmFpW/x3Wt5aTQJsdtk4fjoAfQVdAI+g5c+p2ocf9WB7HjilYT0Wv+svaNQfMj3Vf4f8qelwtqz9aody1XbKMvPVD8d5SnkDocfR+BMuHsY4Pp3oCByqE5RGsb203gG1N4WAFsd4EwnaW1TcHZtJS7plnnnnm2T9nPwFAFOS9Sg2C0QAAAABJRU5ErkJggg==',
 			'image_grey' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD0AAAAyEAAAAACaz1CjAAAEXElEQVR42u3Yf0wbVRwA8MbwxzCNIYElJA7SxDrvj6XpH51uy8UU0j8oNLHOklYJ9RIuSOxi6rJldckytIcFmQRwPzrTSBuLOqhIhOF+dDuRbGRrHMjEklRSpRmHlHCaQy/ZWU5eagPH9fqu1USN3PvrvX7vPu/de/d+VMH/Y5dih96h/xV0wnF1V39FT2mXy/fR4O6ZqpRGHMNFlupme++5767dc3/36ULgt8a/SK/G+g41vWwMCNPh/e3d877NqMXOcc3g0IBVmG7sjT2ZchREs/b+isP7t7Ob6TS3Uszz9PT1L7ajm2nk5g+786aXllvmpNk/W08MPzP4iDScTrfKuUge9Lepl4ZhcO2B17WE50IIRg9Yr1ayv8qkFw9aV2GwMeC8SHhAer8ajl+7nK3lCnEfN6NwuOlyGiY8bZWBV+H47XEZ9MVrcPj562+9l6EJT4fxkhKOLwQg9OLB516E06CXtyYvC6fHXltvzEn3lMppsxAmPO2PX/oSjs8TOeiURs4Ae4XaThOeD96B0+OaHPRMFRw2BlzfiOneZTg9OCQc5wL64wU4XHdODBOezho4PWB94JOku1xwut6ajW6rlEMLe1tAn3oUTtveyEYTnk8eg9OzvZL0iebC6f5zcHr6uCTdRhVOy/m85p6WpGXNZNXZ4Len5fS1cAkV0CNn5Hxcb/4uprsQOfRqvSS9UiyHPvqumL5wBw5/bso5kTrW4LS9T0x/eB5OR77PSY/Vy5lUWncV8rrp6Zx0SiPeBooTbhLS/jE4POmGrtd3G+B07YGTtzbhnjAcHrIzERkbpL5DcPyFpsw47zDKmcfEG4WsdEpzmoPjDUXun8FKHazNdwLNuSNNaeRsGRqe6KjsR+FL5Ryb5xFg2APbKrVRP8Vu7IV9y0t1BRx8VmM9paZT2VnH2tcVIGa9cZ4YuZmd/Yyf7ZXa/ss47q0Uj5w5Wb21/c3o+a/mSrfGrDc+8N15dmsFhuyT7h+rH3b+LYfctYGEY6Yq4WDt0jEPO395ajnERHK19L97tI/Fpqbyvae9XXyPiKaoeDwW4ziev38/Q7Hs5OTGkYiNRBiGZV0uDAN5EAHiotFkMhIBv6cfT9PxeDRK05lykMcwkoTSfn95+b59CKLTIYhpY5nDcfXGpVJxHIJgmNlMUSiq1R47xvM6ncmkUjEMhqnVXm84XFZms3V3j44qlVptWRlJgvKzZ0dHS0p0OpCXQdtsHIeiXm8iUVSUTKrVFDUxoVZznMtlsUSjIKK1ledJsqQEPDwYxDDfxibXYvH7wf0GQzDIMDodoEF5TY3fn87LoMGD0y9IoeC4PXtCIadTpQKvd2ICQWg6FLJYGCaRUKlIMhym6XQsqBhNJxItLRYLSSqVgAblR45k8lD6yhVQ+/Sw0OtB3mBwOm22ZBLH9XqXi+cZxmw2GDjO59PrcTyZTMdSlNOJoqFQPI7jZjOGTU2ly0FlTCaQ3/nfbIf+f9F/AOHCDwE/4NgTAAAAAElFTkSuQmCC',
 		),
 		'debitmastercard' => array(
			'machine_name' => 'DebitMasterCard',
 			'method_name' => 'Debit MasterCard',
 			'parameters' => array(
				'CardType' => 'MCDEBIT',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '510259',
 					1 => '510782',
 					2 => '510840',
 					3 => '510875',
 					4 => '514700',
 					5 => '517869',
 					6 => '518868',
 					7 => '519463',
 					8 => '5141',
 					9 => '5179',
 					10 => '5236',
 					11 => '5262',
 					12 => '5264',
 					13 => '526418',
 					14 => '526471',
 					15 => '526495',
 					16 => '526790',
 					17 => '527432',
 					18 => '5275',
 					19 => '528013',
 					20 => '529964',
 					21 => '531445',
 					22 => '532700',
 					23 => '539738',
 					24 => '5399',
 					25 => '539923',
 					26 => '539941',
 					27 => '539970',
 					28 => '541592',
 					29 => '541597',
 					30 => '542432',
 					31 => '5443',
 					32 => '544440',
 					33 => '544927',
 					34 => '545045',
 					35 => '548901',
 					36 => '548912',
 					37 => '548913',
 					38 => '554827',
 					39 => '557071',
 					40 => '557300',
 					41 => '557361',
 				),
 				'lengths' => array(
					0 => '16',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'Debit MasterCard',
 				'cvv_length' => '3',
 				'cvv_required' => 'true',
 			),
 			'image_color' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD0AAAAyCAYAAADvNNM8AAAElklEQVR42u2ZP0wTURzHH4h/U6QGkHp3xMYwEGVA4+BA4kUHB0wYHDTBiEljSCSmJsYwOKBxINFEBvyTWNobHBg6EMPg0MGBwYGBgYGhA4MDkVarAu3Vozx/r/0dnqXCcfeDGr1f8g1H3rvX93m/9+79fu8x5plnnnnm2X9qaSa1LzIplGLSECiSZrKWYsrTNFMG4a/KGauz0w5/0ezTY62Xcpo0lI0pozlN1op/Y/KDbEy6mo3ISlVBF1kwAKDDKSYnQXwLLYHGF5ncWaktfUzuBrAEyADxLfRB15Qw14IHdhG22VfyaBGEO9DkZ1by2LImd+Zi0rQN0Er6COrbceAFFgiCt2Ydwq4rXSdljTv1b6HTBYfA69KjyoRYFjvkYakLPLzgFjhVAx1VD3Lex7gxVM/dQqNml7VggBhYaYMOp1wDg/TzJWBTxuMGEnA9Js+Qeby0huU5CuDlkw2/ARd1k/H8SDMVeJwEGjo7QgH8pb6Fr12v2QgNWuvfw3NjEgm42NoopnWOZFqrBysCr0/zR4ep1neSP7QXF/zBy1KEysubARe9fQu8HZWppnm/I2ARRVF9vFZO128JLfTjSSOVtxMOvayoFMBCxuV9tqBXBw9RQRuOvuQQhDygAE7vkWwBF6f47b1U0Dwfk3ocJBEiaXAPnWlstg0tti8qaEfrGj5i7yigvwaa7EOD9NfHaMAhU3OyP7+vCvTLAI2nNXnEyfSOVwOaatvKaspg9SIxX8D+hyxUS7amHaWdaSYNUG1Za701tqAL4f1k0CvR1rPbhhaJPhV0/sIBW9DGEFUoKi24STZmKKC/nzhi7yM2epQKOuIGup8qQClcq921qS2OoRxDY/ydJMmlTzVsCp1/1kQDHZXHKfLpbpK1XSPz1Z69lWPu+2Qx95I+prRRnY8Nk2xf/qMbvuTiAIEqCnN9gFBhmk9SgH9TGjm/8SuH1p+3VC/stAdOc6jwTWosFEJ1P/QXLWsUaWQ2Kt/b4cN+Jez2CEmEuCt3j58RtxVu92NxDbRrVzrodWObwDOf2K9OirMskQLibcV20saMmM47dsi/VdSG4WpiE+/PwewYhbrn/rh0AF4k/QD0ZpMBWBLbUS6q9Ga0oP+vub3MsKBf3GCKo6bSTaYzTwio/FhrR05TVLEFVcWjnjkzETB07sLvDFL9jrgYC2LHzUP0jgpQ4r7YXLfiWaR2PnweBmmWcrMNs712UBO+Y75v7bwf+9COz+X1zXLxGyoFdF/xlpaxWdAcaBr/TmK5yGiSqHkEmcMOTOCgTYFmQE/xnWl8fx4HRsP3xWHexWL0y5iIocOgbtASvp9CKLP+AJZnsM0UJfQ4wkxhx8SluoGjnUSwLnyuQ8/G0SNmG2bEpGInzY734nMIy+NY37QE1vEhmFpW/x3Wt5aTQJsdtk4fjoAfQVdAI+g5c+p2ocf9WB7HjilYT0Wv+svaNQfMj3Vf4f8qelwtqz9aody1XbKMvPVD8d5SnkDocfR+BMuHsY4Pp3oCByqE5RGsb203gG1N4WAFsd4EwnaW1TcHZtJS7plnnnnm2T9nPwFAFOS9Sg2C0QAAAABJRU5ErkJggg==',
 			'image_grey' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD0AAAAyEAAAAACaz1CjAAAEXElEQVR42u3Yf0wbVRwA8MbwxzCNIYElJA7SxDrvj6XpH51uy8UU0j8oNLHOklYJ9RIuSOxi6rJldckytIcFmQRwPzrTSBuLOqhIhOF+dDuRbGRrHMjEklRSpRmHlHCaQy/ZWU5eagPH9fqu1USN3PvrvX7vPu/de/d+VMH/Y5dih96h/xV0wnF1V39FT2mXy/fR4O6ZqpRGHMNFlupme++5767dc3/36ULgt8a/SK/G+g41vWwMCNPh/e3d877NqMXOcc3g0IBVmG7sjT2ZchREs/b+isP7t7Ob6TS3Uszz9PT1L7ajm2nk5g+786aXllvmpNk/W08MPzP4iDScTrfKuUge9Lepl4ZhcO2B17WE50IIRg9Yr1ayv8qkFw9aV2GwMeC8SHhAer8ajl+7nK3lCnEfN6NwuOlyGiY8bZWBV+H47XEZ9MVrcPj562+9l6EJT4fxkhKOLwQg9OLB516E06CXtyYvC6fHXltvzEn3lMppsxAmPO2PX/oSjs8TOeiURs4Ae4XaThOeD96B0+OaHPRMFRw2BlzfiOneZTg9OCQc5wL64wU4XHdODBOezho4PWB94JOku1xwut6ajW6rlEMLe1tAn3oUTtveyEYTnk8eg9OzvZL0iebC6f5zcHr6uCTdRhVOy/m85p6WpGXNZNXZ4Len5fS1cAkV0CNn5Hxcb/4uprsQOfRqvSS9UiyHPvqumL5wBw5/bso5kTrW4LS9T0x/eB5OR77PSY/Vy5lUWncV8rrp6Zx0SiPeBooTbhLS/jE4POmGrtd3G+B07YGTtzbhnjAcHrIzERkbpL5DcPyFpsw47zDKmcfEG4WsdEpzmoPjDUXun8FKHazNdwLNuSNNaeRsGRqe6KjsR+FL5Ryb5xFg2APbKrVRP8Vu7IV9y0t1BRx8VmM9paZT2VnH2tcVIGa9cZ4YuZmd/Yyf7ZXa/ss47q0Uj5w5Wb21/c3o+a/mSrfGrDc+8N15dmsFhuyT7h+rH3b+LYfctYGEY6Yq4WDt0jEPO395ajnERHK19L97tI/Fpqbyvae9XXyPiKaoeDwW4ziev38/Q7Hs5OTGkYiNRBiGZV0uDAN5EAHiotFkMhIBv6cfT9PxeDRK05lykMcwkoTSfn95+b59CKLTIYhpY5nDcfXGpVJxHIJgmNlMUSiq1R47xvM6ncmkUjEMhqnVXm84XFZms3V3j44qlVptWRlJgvKzZ0dHS0p0OpCXQdtsHIeiXm8iUVSUTKrVFDUxoVZznMtlsUSjIKK1ledJsqQEPDwYxDDfxibXYvH7wf0GQzDIMDodoEF5TY3fn87LoMGD0y9IoeC4PXtCIadTpQKvd2ICQWg6FLJYGCaRUKlIMhym6XQsqBhNJxItLRYLSSqVgAblR45k8lD6yhVQ+/Sw0OtB3mBwOm22ZBLH9XqXi+cZxmw2GDjO59PrcTyZTMdSlNOJoqFQPI7jZjOGTU2ly0FlTCaQ3/nfbIf+f9F/AOHCDwE/4NgTAAAAAElFTkSuQmCC',
 		),
 		'maestro' => array(
			'machine_name' => 'Maestro',
 			'method_name' => 'Maestro',
 			'parameters' => array(
				'CardType' => 'MAESTRO',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '5018',
 					1 => '5020',
 					2 => '5038',
 					3 => '6304',
 					4 => '6759',
 					5 => '6761',
 					6 => '6762',
 					7 => '6763',
 					8 => '6764',
 					9 => '6765',
 					10 => '6766',
 					11 => '564182',
 					12 => '633110',
 					13 => '6333',
 				),
 				'lengths' => array(
					0 => '12',
 					1 => '13',
 					2 => '14',
 					3 => '15',
 					4 => '16',
 					5 => '17',
 					6 => '18',
 					7 => '19',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'Maestro',
 				'cvv_length' => '3',
 				'cvv_required' => 'false',
 				'issuer_number_length' => '2',
 				'issuer_number_required' => 'false',
 			),
 			'image_color' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD0AAAAyCAYAAADvNNM8AAAE3klEQVR42u2ZXWgcVRTHF3ZXAgpWqHTZnexcZmbXaMXGqqiYh60BpSBGiVih6IIrRght/SCNNsIW/KgQS8BYtERZdjc0iA8R+6BYytaPJkUKQQLmIcIWs84M7YMPQfrQh/H8JzN1a9JkMnOSLXQOHObuzJ2753fOvXfuPTcSCSWUUEIJ5SYVPZntMJNawUhqRT2pjRmpTMmQMsOGpA3Ssy4rkot5aqhU3xItmz2xqlGMlo3RaMUo4Rqr6EN0f0/khL61paAXhUgQ0BECnCe11tBFM5WtNlKZzmUN1ayYDVoxaqRXSK019Od4xejHe5sHe+f22xBRgHiAXaampE02UpqEtiiiOYrmvAfQZYr37OhvtBiJDkGGz/qBbdY/09v/eeLw1KQf2BXgSzQs2jZq3HaRwZeCAjfa77GOPf+J9daBmvXQx79bHOCxsjEdKV1M8HZpSdXMVObvoMB/td9lHX/uqA3s6v1H53jAaU5gG+f2GE5l5oICQ795fP81wNA3Xj9jJT+vs4BHy/oYCzRFeIQDePbunDWw7/QyaOjLg2etW8o6T8RpcgzcrcngyxzQ7ji+nj7ANb6r5kzAycteaLBEeTVg6KsDv7BFG999X8BYRXHM1tCJ3YfWhIYqx/5ggY5Xzaq/b7Kk5TiAoe8XvvIEvevD37hm8kVfM7meygxxAF8QOzwBQ/cOneOCtuLj5iPrjzQ2DQzQ53fs9gy9/80f2aB9jWsjmf2OA3pq57OeoaFskS7rfX4iXeOAPv3oiy2BxnbUD/QEB/SvnU+1BJr24fmWrsS8Ar9y8CxfpP2szGj27uP6ZB3c94Mn6GeK59mgI+MNad3Q2OhzQY++8KknaLYdV5ClKBk8zQF96rH8msDYbd3xxQJX1y4GgM7mOaCROHj3tZOrQvccZuval3117eb1N01oMxzg3+7qXzXK245f4MqiDDPsp7PdHNDImnyUr6wI3c235jZuPXRimxBiS/CE4FL2k+Xz9U7/99cAv/T2FNeW8gotSLpgrybEkzyZUKbFyrmdT1/NoCBjcvuXC61bjHgc3yNcq7TCwBku4MVoVe/d0Nw3jm8CZ0YlbfThD366D6nbgMBz8VKjc1NOOeo0UThRX2/+rIb8eXNb6JY+TjmMeMUsbOrRTtMBwFZd0vbiqIa2osYKkHDKNDnoPbNdvXe1tmLjZjdSuIje9aJKzvkM9TbsRCPQ0Q+pe17lSyiCbSVDQNlPLkK5AQSLClWI/I1soNCoC9NCIJejzxkMdsvO8zYsEuiaIO34372rYzIjRCcUZQLeo8hiBu2gDu7jffptJ/mUdLqbyj05r4f83KKkRVGV5Uukp1RZ1DVZzNmaFiMwmO4vaLJ8kq6LalqUsjTp2fWpjPownJ73oz7dH7OdaJdFHW3bUZdFjXQasKijyHKVrsP4z5ZBQ2E8GTGLaMBwGIpouIZR9PpsUFxled6BNhyQYcdRRwDpvu/+h92WMyHCGc333d6z+ZF2xp9jhGguI8IUmV5AAVRpVx6Ec1T6dKH7OmvknCqpmtOl4bQE4AC05Mz/HIA6SlrpwjM4j2VTsV5BNGGE44BBdF+3vGSk3Ev6Nf0+QN244EQ9TyCTuAcoXFHHHdNLTpOHUAcOcNty5gOCFRMYMg68gMPC6T+UUEIJ5WaRfwEXqiK6EJFqegAAAABJRU5ErkJggg==',
 			'image_grey' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD0AAAAyEAAAAACaz1CjAAAD/ElEQVR42u3YbWhSURgA4LV+BfWj/QiTRT8GfS2o1VqBxoqlkVK0IHCORqu5NUY1yMTPogWtkJWMZKUlZYxoSa2tIbFoGAuWGw1ciMEWijjjLm130j6Y7uTNPnbV63u0oKj5wuWC5/p47ns9533NQn/slbVAL9B/Be2z9RRYrIaNN/JbmzoaXOaIMHHMVK1T/4Ld4baQHe4ep6Pq8/1fpMfv3j9VHylT06OyVO9wq76PmSt06m9JNZtUbHoYKvrK5wozoqeXW6yVpfHsz7i6O3AToff2JkM8+jOaDI6qtGliTLadmY3F0aB5KTP7PSzk7NM0aJe5WgvB4sP1qxR2pR/GW4ZCOzHpD21VRSAsOK1T2KlQ1sD4LWmyrGcl5viMAYLL1CcOxuBo+JSjMP7IiEGbX8NwhUM++4O2K1eosmD8vR2gP7RVdME0leX5odwP09fbAdqwEWfOdDgaCGfeTn0KOiKEn+wydV1uAm1XbYbph+0paOchGC5Tnz2QSCslMH1BTH/OafTjyzBcrkiEo3Q7TKvY3vWM9I18mK7cl5Teh0PTs02jL6+D6WP5yehotjFo+1pG+iIfpqumMqd7nIx08wTGDTdlTr9hnjXeSpY01ytxaPqKRqOf38b5ccnHk9BbcWjyCCMduIlD148loWvSX0rjFtJzy2Ba8iqB9qkWw/QLdkr65SKYFh+WzcXNWQPD53n0251AR4SKHBivmaXPWdkJ01YOuF+/FWPMW3BGNG/OCIYbjWQ1GQILJIsVq1g4/Y1ugTdMzSZ3J0I2nLIQZ2k5dpKqVPAqFPpSkpKOCHEWl0qT/B4MXxC/HUizBegpgOrSO3tHx1uGUsPXJkbvZdD4fNaaXzNVahf5LnNs1Ju1TP1Ho3FAl6r1Adq9CXev+eruE89+PF5d55a1Gb1K+qiRukfGaxPz5/pk9UgdU9eRdpNLjBFjVJfF/Jor/FT8qTh5p/FvtvZkyBL4rbTX7+f15YYlZIg6RpuiGRsiAsOe2Nn0zNcqNs+Zh1DnR0F05PSMM48IDLIQ6p3szqauyJjWFRWMlE9xNSW2EluDbHpmh+f4lg0uKScoLhiRcriasMS8p0EmX+L1N8i4Gl0RGRJpSxt7J+VL6ocu5ZRP/RKtKwpL+MQgy+sXabuzqQ9rDUo5rcFin5SzTdE7eSmnxHZFTYao96krRFo/DyGuJnZO3Z+MaSqDIq3XHztucFlZJTYpx7GGT7zj9+Ui1Jfr5gh4gywiwNUMe8KS2BcQ8Pq5w55iX/yGkQbdnd3PRahlV1BMHRGysmoHTMIH/OgWE6huNgnDEpOwdoDKNULX2dXNRIAahdCw56T++JZ+rtfv5iz8b7ZA/6/0FxX7gz2SYuOMAAAAAElFTkSuQmCC',
 		),
 		'visaelectron' => array(
			'machine_name' => 'VisaElectron',
 			'method_name' => 'Visa Electron',
 			'parameters' => array(
				'CardType' => 'UKE',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '4026',
 					1 => '417500',
 					2 => '4405',
 					3 => '4508',
 					4 => '4844',
 					5 => '4913',
 					6 => '4917',
 				),
 				'lengths' => array(
					0 => '16',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'Visa Electron',
 				'cvv_length' => '3',
 				'cvv_required' => 'true',
 			),
 			'image_color' => 'data:image/png;base64,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',
 			'image_grey' => 'data:image/png;base64,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',
 		),
 		'americanexpress' => array(
			'machine_name' => 'AmericanExpress',
 			'method_name' => 'American Express',
 			'parameters' => array(
				'CardType' => 'AMEX',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '34',
 					1 => '37',
 				),
 				'lengths' => array(
					0 => '14',
 					1 => '15',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'American Express',
 				'cvv_length' => '4',
 				'cvv_required' => 'true',
 			),
 			'image_color' => 'data:image/png;base64,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',
 			'image_grey' => 'data:image/png;base64,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',
 		),
 		'diners' => array(
			'machine_name' => 'Diners',
 			'method_name' => 'Diners Club',
 			'parameters' => array(
				'CardType' => 'DC',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '300',
 					1 => '301',
 					2 => '302',
 					3 => '303',
 					4 => '304',
 					5 => '305',
 					6 => '309',
 					7 => '36',
 					8 => '38',
 					9 => '39',
 				),
 				'lengths' => array(
					0 => '16',
 					1 => '14',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'Diners Club',
 				'cvv_length' => '3',
 				'cvv_required' => 'true',
 			),
 			'image_color' => 'data:image/png;base64,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',
 			'image_grey' => 'data:image/png;base64,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',
 		),
 		'jcb' => array(
			'machine_name' => 'Jcb',
 			'method_name' => 'JCB',
 			'parameters' => array(
				'CardType' => 'JCB',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '3528',
 					1 => '3529',
 					2 => '353',
 					3 => '354',
 					4 => '355',
 					5 => '356',
 					6 => '357',
 					7 => '358',
 				),
 				'lengths' => array(
					0 => '16',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'JCB',
 				'cvv_length' => '3',
 				'cvv_required' => 'true',
 			),
 			'image_color' => 'data:image/png;base64,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',
 			'image_grey' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEEAAAAyEAAAAAB2ujW1AAAEQUlEQVR42mP4P+CAYdQJ2JxwXWPZvH63dpNO5i757nc9xX3O/dUT9SZJTZG8IY+s7vKh1XaTp3Xr90h0X+zZ1TOht6p3bp9ln33f7cdrkdXd3Ll16eIXC3Yt3rTk6LI9Ky6sOr5m37pLG55vuvdWE4sTrvHGbTRfaaVr02in6LDU6Y5rvDuDZ4j3DT/+ANegkwfWw9VppCY5HXe+4HzT5ZHrOdcvbg/c2dw/efzxZPE86LX2UilM3dW35U2xzxN1UkLSq7Ot8kQKbUqSy7urUutUGjNbJjz8iOGEPRPMck2LCDvh4H2XbMfbhJ2wwz1qXjQvCU64vc88zdiasBOu7nY+47iVsBOuqIWXhz8lyQnpXAbbiXFC1nNHMWKcUOQUso8kJzzK1Rcnxgn3JjksJsYJ1yYFTiPRCTuciHPCjhzinLB5OslOWLKOOCcsYCHOCStkSHbCtGejThh1wqgTKHNCdF3+8eKWktySu2mfgnpp4oRG6xddLz695H+57OXha88QTogXWyJ4rfSvJ2ol/7HiksKGpKj1ECd8lXll8arl1cXXGa+3vvF5U/3mxNv4t2teZl2Zu0idBCd0IbUPXkVBnLCmdHc+vuZHeSByZY0dnP1CkROQwYc78xXSf4VEBp5I/tTIuenT66OYTmiJbHve8bvbfgXjT1aE6IxvVHHCI//QONTk6P2nKSMtGdUJiLSwuQYhuu4SVZyQNoNwqwnZCeveUTkUrpUS03BDRMQ8vy+RMLELtVRJCzv5iHMCOniYuOp4jfWAOuH//xcxG2Wp4oQLIcQ5AZYWMp8jJ8fDBUQ6Yb4YUm+gCdUJfz1jtUlLjtlWX5bBRP9pdU/EcMJRdc9yVCd4XnrRhTBqtSJ6jrh8yOcjuhOydRPW43JCyYLfhQjx+TOwtKD/nD1Z2nYlYobVbZATsm/c+IHQ8GV3ohxm0fRodvv+gByQE3y8Mk/PYbyhiLtomjLrzkOE6O997XOxdmXgpd4utNL/avkdRPP1Miuq7HedL9qkFdD//295iSUtXNx/6Ov7F5iKn/Esb4piQq0py4X3RHy+g6n29dHjc9EjAh18F7pTPf8OnhzhbBq3saCq5X6bRLtJ68Wi2BA73JV11MGiQ12lkG5tHXPm6eCq0VbTqBNGnUBnJxxdM+BOAA13EeOEW3eIcwLacBdhJ4R6fM0nxgkBib98iHFCnxLaoB9hJ/Swg9QRdsLE2yB1hJ2waxaJTogofd5PjBNCJ72JI8YJ3RO/5ZHkhIDeY9Ckg98JgTsusEPU4XdCS8TDCxijr/ickFF5zQimHJ8Tsvfdug5Th88JU9Nf9mAZAN44ObkgJSZNJ/13Zl327FzrfK6CW0WxDaoz5M/O+/MKoXxHTsGCQoFCscK2ovyiQ8UikG5tI8uMlPNbkI3dV11bUL+l8QesydLbNKFqMseCpVt47lSPjsQPUicAABwRReLi5DATAAAAAElFTkSuQmCC',
 		),
 		'lasercard' => array(
			'machine_name' => 'LaserCard',
 			'method_name' => 'Laser Card',
 			'parameters' => array(
				'CardType' => 'LASER',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 			),
 			'credit_card_information' => array(
				'issuer_identification_number_prefixes' => array(
					0 => '3528',
 					1 => '6304',
 					2 => '6706',
 					3 => '6771',
 					4 => '6709',
 				),
 				'lengths' => array(
					0 => '16',
 					1 => '17',
 					2 => '18',
 					3 => '19',
 				),
 				'validators' => array(
					0 => 'LuhnAlgorithm',
 				),
 				'name' => 'Laser Card',
 				'cvv_length' => '3',
 				'cvv_required' => 'false',
 			),
 			'image_color' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC8AAAAyCAYAAADMb4LpAAAHG0lEQVR42tVZ+1NUVRz3n8k0MHyBgALyEknzMdX0csryMTpZOWKZuuDK7rK7KIXPiLHUTBSKBoWBCmREKzExUkaMEkEHHXMcHS1Lp3N3+37vPd97z32wz7vM9MPHs55z7jnf8318vt9zGBcMBq9EgfuAEf77AeAGYJD/H3/f4/3DQotjf3BcFdbC8esACTDE+x7xb/7m7V98Do7d4WvQ94Pj4B+2IGd7RFiYs11aOLsqIP8WWiPEcfU7gjhXWE9dS+hblFsVNM6nuTt8bUwWPvWJzREj7UlHIHW8Q9IBx6DFMXmc5tF3fJ7YR+vkJ5UF500sZYj5SWWB+U+VSYuSywJvppSxZZO3yC1h7RQn2zBNwe61RwOy8DT43lRl4KP0chM+yXCxw7NkSHXZ7iD97pjtDbTnVMgt4cf8CtZX5JX6inwBsb01rzKgwC/dml8Z1Freh+0zfqa1vE9soX+o2M+2l9RLYTWfPX4zI82IWDpJ08iylC0Stm8LmkF8MM0pIQyKkAzKUJVyZJabgSJM+KmgQsLDX5zjZQhX6la2dUNj9G4jYu5Eh+lQS4RDWZmbgAIYrbvXfCDWkKU/EPbh3qrwu+DDw1kuGXVw+qYcNzsG6Mj1qDgDrtANuFjo1aEPNDE416fDMEA2ewJQD4fBgx5cfTA+zUeDTAv3ez7ZbKXVkxXLlINlPGkKqmeUs9pMF6uC37SeqnnVlDDoA+zKKNehBj48OEuPhiwPWEiPjjwP6xRwMq+CnSvw6nABLPUbWEbEcHF4S40AUDaStXbNF0zHNogVgHXAOiJKp+PBNHhSzQfcN9Oluh6hGVyvVUBHbgVDJhKBbne5SMPvRdYHOQLriXLuWHskcW5j5SZLDcFsZKdN0800vR+UgspJN6yvuo0Y3XWAFq6pdnCFTgjWrjwlYM8CegsVXEbzg6aGeYDeKPYnLEi/AzmMLFS/cr/9mg9HnUbadBq0vQOZTxAS/2+1j6p5qyQk4rmkUvY6CEFYmeJkJVM1oBDEDIhtAGQHwmeZbtaYpeF4tpt1gf8T0PcvgSUJA3MUv78BWA4HtpLJv75BzzYi/DO0oPzYwDZ1EDwiy7Tm6Fmmu0DPMpeL9OwSqbu0wLpWsunYZix4njBHyMoLwaLLBXZzgFDb0hWF+UB5WJqMto7qNlaFGOEAp0DMuk086yJ/YwATekNk3GvFsWXbxmx3SLnizrC5E/RaHC0wjRQo5wQekMZirAfcrDHbE3bvuAszqwLt5WRzqrc6wOcCozSDb5PwXWBVXC9i4Y0casRRwHFwGeT8TtltPCrfI/oxS3Jc5bwfK/djLgknT8J4fjTkCy72IrfOW+BaGznNVkHxtRMsEipILTU/Gh0ZUZWu1TKYtok6iTKP5VgXZRjQRJNDIQIY14xUFpUqwyUpc9ZUkhbSHCUqx3QtUe3O0BKUVWKCm5GakEa4a50Cd4lGBjVJJZLPFwssRAwkBi8yDwYvMlc060fE8+armnLrwhsN3bJ6OMdjSRsLp38PWo9GhoTfpIg+KTiXP635q2+G/s4ardZ1mjdexSLFmilKWt/MLyteXg/tydTuxHQROZWnFGAXsJwGCw3xGqcG5sayt3oZiTTCRZTz6+KeDIVxDvFijRiH2IaYZtCiIMOxjTHsPaaFGQUv3qRWpSisVA1u82ySI+Y1I86wodDKM+85cIlfCr26G9ZICE7/ubAirn0TdpOiJ411UzRapBeyb3gdkz/BEdc+thVmRhchbse3T7rmUQF2AmIB+T7efWLieSsgY3wJGfQEf1lD9xkA17Gq5fuhvzrO/cbsxSydu9I7UB5XppXLVrFj3agLs3DAqhApk+gSqRJp8ibXOtKnXXvFXJiFK9rWg0/jC8KBmW7WBgc5X4DvPD72yiT79kloYYZAF8HgxLcXvGHZubYtPG/1no5P4deEjDoIQVpn4x5jcpPCogz/iGC31m0pzEIBizV82TW+7tqFuAqzcMD3e3x3dwlv6nYi4YVZJE8YCSsP8I+1tTtPsLxpLsvxwjSPPCd9gjnxYN9ri/eavqVvCLrDZnrlPpwTt/Crluxj9Bdyq/FK53F5vLSkQdc/M9nJbo7cZX2911lvz7BurLnxPHtw/x/W032VfdtyQTeGfXdu/8kYk9iVgVvyOgkTHoV7/Ohf1t83oq8ss/zs4cPH7EBNl8kqKPxA/015bZxnFB7HvaVN8r4rXqpNjPDoEqR1q402vXtUPsChfadH1fyrC3abhD/d+auskLM/XLHHbd54ocbki61NvbJ50T1wTkfbJZ3b4Py9H7bLgojmR+HRYuTz4hhp/tM9J9m9uw/lGIhZeBQahSNsWFMn92MQDg3eZs73v5IFcG36ml0fvqMeDjWKroRzqr1tujXRErSe0a8xBnAc+zBW6g+dGdvLyFhBFR7p8P+Gk+397D9cv7gDsg2AeQAAAABJRU5ErkJggg==',
 			'image_grey' => 'data:image/png;base64,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',
 		),
 		'paypal' => array(
			'machine_name' => 'PayPal',
 			'method_name' => 'PayPal',
 			'parameters' => array(
				'CardType' => 'PAYPAL',
 			),
 			'not_supported_features' => array(
				0 => 'IframeAuthorization',
 				1 => 'PaymentPage',
 				2 => 'Recurring',
 				3 => 'AliasManager',
 			),
 			'image_color' => 'data:image/png;base64,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',
 			'image_grey' => 'data:image/png;base64,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',
 		),
 	);

	private $globalConfiguration = null;
	
	public function __construct(Customweb_Payment_Authorization_IPaymentMethod $paymentMethod, Customweb_SagePay_Configuration $config) {
		parent::__construct($paymentMethod);
		$this->globalConfiguration = $config;
	}
	
	/**
	 *        		   	    	 		 
	 * @return Customweb_SagePay_Configuration
	 */
	protected function getGlobalConfiguration() {
		return $this->globalConfiguration;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Customweb_Payment_Authorization_AbstractPaymentMethodWrapper::getPaymentInformationMap()
	 */
	protected function getPaymentInformationMap() {
		return self::$paymentMapping;
	}
	
	/**
	 * This method returns a list of form elements. This form elements are used to generate the user input. 
	 * Sub classes may override this method to provide their own form fields.
	 * 
	 * @return array List of form elements
	 */
	public function getFormFields(Customweb_Payment_Authorization_IOrderContext $orderContext, $aliasTransaction, $failedTransaction, $authorizationMethod) {
		return array();
	}
	
	/**
	 * This method returns the parameters to add for processing an authorization request for this payment method. Sub classes
	 * may override this method. But they should call the parent and merge in their own parameters.
	 *
	 * @param Customweb_SagePay_Authorization_Transaction $transaction
	 * @param array $formData
	 * @return array
	 */
	public function getAuthorizationParameters(Customweb_SagePay_Authorization_Transaction $transaction, array $formData, $authorizationMethod, Customweb_DependencyInjection_IContainer $container) {
		$parameters = array();
		$cardType = $this->getPaymentMethodType();
		if ($cardType !== NULL && !empty($cardType)) {
			$parameters['CardType'] = $cardType;
		}
		return $parameters;
	}
	
	public function getPaymentMethodType() {
		$params = $this->getPaymentMethodParameters();
		if (isset($params['CardType'])) {
			return $params['CardType'];
		}
		else {
			return NULL;
		}
	}
	
	public function getSpecialIframeParameters(Customweb_SagePay_Authorization_Transaction $transaction, array $formData, Customweb_DependencyInjection_IContainer $container){
		return array();
	}
	
	public function getIframeHeight(){
		return 1100;
	}
}